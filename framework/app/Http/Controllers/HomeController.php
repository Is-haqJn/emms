<?php

namespace App\Http\Controllers;

use App\CallEntry;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$index['page'] = '/home';
		$breakdown_totals = $preventive_totals = $total_days = [];
	
		$last_thirty_days = date('Y-m-d', strtotime('-30 days'));
	
		$breakdown = CallEntry::selectRaw("date_format(created_at, '%Y-%m-%d') as date, count(*) as total")
			->where('call_type', 'breakdown')
			->whereDate('created_at', '>=', $last_thirty_days)
			->groupBy('date')
			->get()
			->keyBy('date')
			->toArray();
	
		$preventive = CallEntry::selectRaw("date_format(created_at, '%Y-%m-%d') as date, count(*) as total")
			->where('call_type', 'preventive')
			->whereDate('created_at', '>=', $last_thirty_days)
			->groupBy('date')
			->get()
			->keyBy('date')
			->toArray();
	
		for ($i = 30; $i >= 1; $i--) {
			$date = date("Y-m-d", strtotime('-' . $i . ' days'));
			$total_days[] = $date;
			$breakdown_totals[] = isset($breakdown[$date]) ? $breakdown[$date]['total'] : 0;
			$preventive_totals[] = isset($preventive[$date]) ? $preventive[$date]['total'] : 0;
		}
	
		$index['total_days_array'] = $total_days;
		$index['breakdown_totals'] = $breakdown_totals;
		$index['preventive_totals'] = $preventive_totals;
		
		return view('home', $index);
	}

}