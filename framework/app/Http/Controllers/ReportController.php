<?php

namespace App\Http\Controllers;

use App\CallEntry;
use App\Equipment;
use App\Hospital;
use Auth;
use Excel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use PDF;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
// use Illuminate\Contracts\View\View;



class ReportController extends Controller {

	public function __construct() {
		$this->middleware('stripEmptyParams');
	}

	public function time_indicator() {
		$this->availibility('View Time Indicator Report');
		$index['page'] = 'reports/time_indicator';
		$index['hospitals'] = Hospital::query()->Hospital()->pluck('name', 'id');
		$index['equipments'] = Equipment::query()->Hospital()->pluck('unique_id', 'id');

		// $index['call_entries'] = CallEntry::leftJoin('equipments', 'call_entries.equip_id', '=', 'equipments.id')
		// 	->leftJoin('hospitals', 'equipments.hospital_id', '=', 'hospitals.id')
		// 	->select('*')
		// 	->paginate(10);
		$index['call_entries'] = CallEntry::query()->Hospital()
			->paginate(10);
		return view('reports.time_indicator', $index);
	}

	public function time_indicator_filter(Request $request) {
		$this->availibility('View Time Indicator Report');
		$index['page'] = 'reports/time_indicator';

		$index['hospitals'] = Hospital::query()->Hospital()->pluck('name', 'id');
		$index['equipments'] = Equipment::query()->Hospital()->pluck('unique_id', 'id');
		$index['hospital'] = $request->hospital;
		$index['equipment_selected'] = $request->equipment;
		$index['call_type'] = $request->call_type;
		$index['call_flow'] = $request->call_flow;
		$index['from_date'] = $request->from_date;
		$index['to_date'] = $request->to_date;
		$from_date = date('Y-m-d H:i', strtotime($request->from_date));
		$to_date = date('Y-m-d H:i', strtotime($request->to_date));
		$call_entries = CallEntry::query()->Hospital();
		if (isset($request->hospital) && $request->hospital != "") {
			$call_entries->where('equipments.hospital_id', $request->hospital);
		}
		if (isset($request->equipment)) {
			$call_entries->where('equipments.id', $request->equipment);
		}
		if (isset($request->call_type)) {
			$call_entries->where('call_entries.call_type', $request->call_type);
		}
		$var = 'call_entries.' . $request->call_flow;
		if (isset($request->call_flow) && isset($request->from_date) && isset($request->to_date)) {
			$call_entries->whereBetween($var, [$from_date, $to_date]);
		}
		if (isset($request->excel_hidden)) {
			$call_entries = $call_entries->get();
			$call_entries_update=[];
			foreach($call_entries as $call_entrie){
			
				$call_entrie->next_due_date = date_change($call_entrie->next_due_date);
				$call_entries_update[]=$call_entrie;
			}
             $call_entries= collect($call_entries_update);
			// return Excel::download('time_indicator_report', function ($excel) use ($call_entries) {
			// 	$excel->sheet('sheet1', function ($sheet) use ($call_entries) {
			// 		$sheet->loadView('reports.export_time_indicator_excel')->with('call_entries', $call_entries);
			// 	});
			// })->download('xlsx');
			return Excel::download(new class($call_entries) implements FromView
            {
                public function __construct($collection)
                {
                    $this->collection = $collection;
                }

                public function view(): View
                {
                    return view('reports.export_time_indicator_excel')->with('call_entries', $this->collection);
                }
            }, time(). 'Time_indicator.xlsx');
			// return Excel::download(new class($call_entries) implements FromCollection{
      //           public function __construct($collection)
      //           {
      //               $this->collection = $collection;
      //           }
      //           public function collection()
      //           {
      //               return $this->collection;
      //           }
      //       }, time() . 'Time_indicator.xlsx');
		} elseif (isset($request->pdf_hidden)) {

			$call_entries = $call_entries->get();
			$pdf = PDF::loadView('reports.export_time_indicator_pdf', ['call_entries' => $call_entries]);
			return $pdf->download('time_indicator_report.pdf');
		} else {
			$index['call_entries'] = $call_entries->paginate(10);
		}
		return view('reports.time_indicator', $index);
	}

	public function ajax_to_get_equipment(Request $request) {
		if ($request->ajax()) {
			if ($request->hospital_id) {
				$equipments_filter = Equipment::where('hospital_id', $request->hospital_id)
					->pluck('unique_id', 'id');
			} else {
				$equipments_filter = Equipment::query()->Hospital()->pluck('unique_id', 'id');
			}
		}
		return response()->json(['equipments_filter' => $equipments_filter], 200);
	}

	public function equipment_report() {
		$this->availibility('View Equipments Report');
		$index['page'] = 'reports/equipments';
		$index['hospitals'] = Hospital::query()->Hospital()->pluck('name', 'id');
		$index['call_entries'] = CallEntry::query()->Hospital()->paginate(10);
		return view('reports.equipment_report', $index);
	}

	public function equipment_report_post(Request $request) {
		$index['page'] = 'reports/equipments';
		$index['hospitals'] = Hospital::query()->Hospital()->pluck('name', 'id');
		$index['hospital'] = $request->hospital;
		$index['working_status'] = $request->working_status;
		$index['excel'] = $request->excel;
		$equipment = Equipment::where('working_status', $request->working_status)->get();

		$call_entries = CallEntry::query()
			->join('equipments', 'call_entries.equip_id', '=', 'equipments.id')
			->select('call_entries.*')
			->where('equipments.working_status', $request->working_status);
	
		if (isset($request->working_status)) {
			$call_entries->where('equipments.working_status', $request->working_status);
		}
		if (isset($request->hospital)) {
			$call_entries->whereHas('equipment', function ($q) use ($request) {
				$q->where('hospital_id', $request->hospital);
			});
		}
		if (isset($request->excel_hidden)) {
		    //   dd($call_entries->get());
            $call_entries = $call_entries->latest()->get();
			$call_entries_update=[];
			foreach($call_entries as $call_entrie){
			
				$call_entrie->next_due_date = date_change($call_entrie->next_due_date);
				$call_entries_update[]=$call_entrie;
			}
             $call_entries= collect($call_entries_update);
			// return Excel::download('equipment_report', function ($excel) use ($call_entries) {
			// 	$excel->sheet('sheet1', function ($sheet) use ($call_entries) {
			// 		$sheet->loadView('reports.export_equipment_excel')->with('call_entries', $call_entries);
			// 	});
			// })->download('xlsx');
			return Excel::download(new class($call_entries) implements FromView
            {
                public function __construct($collection)
                {
                    $this->collection = $collection;
                }

                public function view(): View
                {
                    return view('reports.export_equipment_excel')->with('call_entries', $this->collection);
                }
            }, time(). 'report.xlsx');
			
		} elseif (isset($request->pdf_hidden)) {

			$call_entries = $call_entries->get();
			$pdf = PDF::loadView('reports.export_equipment_pdf', ['call_entries' => $call_entries]);
			return $pdf->download('equipment_report.pdf');
		} else {
			$index['call_entries'] = $call_entries->paginate(10);
			return view('reports.equipment_report', $index);
		}

	}

	public static function availibility($method) {
		if (\Auth::user()->hasDirectPermission($method)) {
			return true;
		} else {
			abort('401');
		}
		// $r_p = Auth::user()->getPermissionsViaRoles()->pluck('name')->toArray();
		// if (Auth::user()->hasDirectPermission($method)) {
		// 	return true;
		// } elseif (!in_array($method, $r_p)) {
		// 	abort('401');
		// } else {
		// 	return true;
		// }
	}
}
