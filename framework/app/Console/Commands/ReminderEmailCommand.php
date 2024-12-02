<?php

namespace App\Console\Commands;

use App\Calibration;
use App\CallEntry;
use App\Notifications\CalibrationsReminderEmail;
use App\Notifications\PreventiveMaintenanceReminderEmail;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class ReminderEmailCommand extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'reminder:emailsend';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Reminder of calibrations or preventive maintenance email';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$date = date('Y-m-d', strtotime('+6 days'));

		$preventive_reminder = CallEntry::where('call_type', 'preventive')
			->where('next_due_date', '<=', $date)
			->where('next_due_date', '>=', date('Y-m-d'))
			->get();
		$calibrations_reminder = Calibration::where('due_date', '<=', $date)
			->where('due_date', '>=', date('Y-m-d'))
			->get();

		$users = User::whereHas('role', function ($q) {
			$q->where('name', 'Admin');
		})->get();
		
		if ($preventive_reminder->count() && $this->email_setup()) {
			Notification::send($users, new PreventiveMaintenanceReminderEmail($preventive_reminder));
		}
		
		if ($calibrations_reminder->count() && $this->email_setup()) {
			
			Notification::send($users, new CalibrationsReminderEmail($calibrations_reminder));
		}
	}

	public function email_setup() {
		if (!empty(env('MAIL_DRIVER')) &&
			!empty(env('MAIL_HOST')) &&
			!empty(env('MAIL_PORT')) &&
			!empty(env('MAIL_USERNAME')) &&
			!empty(env('MAIL_PASSWORD')) &&
			!empty(env('MAIL_ENCRYPTION'))
		) {
			return true;
		}
		return false;
	}
}
