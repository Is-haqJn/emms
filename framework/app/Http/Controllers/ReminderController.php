<?php

namespace App\Http\Controllers;

use App\Calibration;
use App\CallEntry;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ReminderController extends Controller {

	// public function preventive_reminder() {
	// 	$date = [
	// 		date('Y-m-d', strtotime('+6 days')),
	// 		date('Y-m-d', strtotime('+1 days')),
	// 	];
	// 	$index['page'] = 'preventive_maintenance_reminder';
	// 	$index['preventive_reminder'] = CallEntry::query()->Hospital()->where('call_type', 'preventive')
	// 		->orderBy('next_due_date', 'asc')->where('call_attend_date_time',null)
	// 		->paginate(10);

	// 	return view('reminder.preventive_maintenance_reminder', $index);
	// }
	// public function calibrations_reminder() {
	// 	$index['page'] = 'calibrations_reminder';
	// 	$index['calibrations'] = Calibration::query()->Hospital()->orderBy('due_date', 'asc')
	// 		->paginate(10);
	// 	return view('reminder.calibrations_reminder', $index);
	// }


	public function preventive_reminder()
	{
		$index['page'] = 'preventive_maintenance_reminder';
		$index['preventive_reminder'] = CallEntry::query()->Hospital()
			->where('call_type', 'preventive')
			->whereDate('next_due_date', '<=', Carbon::now()->addDays(20))
			->whereNotExists(function ($query) {
				$query->select(DB::raw(1))
					->from('call_entries as ce2')
					->whereColumn('ce2.equip_id', '=', 'call_entries.equip_id')
					->where('ce2.call_type', '=', 'preventive')
					->where('ce2.created_at', '>', DB::raw('call_entries.created_at'))
					->whereNull('ce2.deleted_at');

				})
				// ->whereNull('call_entries.deleted_at')
			->orderBy('next_due_date', 'asc')
			->paginate(10);

		return view('reminder.preventive_maintenance_reminder', $index);
	}

	public function calibrations_reminder()
	{
		$index['page'] = 'calibrations_reminder';
		$index['calibrations'] = Calibration::query()->Hospital()
			->whereDate('due_date', '<=', Carbon::now()->addDays(20))
			->whereNotExists(function ($query) {
				$query->select(DB::raw(1))
					->from('calibrations as cal2')
					->whereColumn('cal2.equip_id', '=', 'calibrations.equip_id')
					->where('cal2.created_at', '>', DB::raw('calibrations.created_at'))
					->whereNull('cal2.deleted_at');
			})
			->orderBy('due_date', 'asc')
			->paginate(10);

		return view('reminder.calibrations_reminder', $index);
	}
}
