<?php

namespace App\Http\Controllers;
use App\CallEntry;
use App\Department;
use App\Equipment;
use App\Hospital;
use App\Http\Requests\BreakdownCreateRequest;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Excel;
use Illuminate\Support\Collection;
use PDF;
class BreakdownController extends Controller {

	public function index() {
		$this->availibility('View Breakdown Maintenance');
		$index['page'] = 'breakdown_maintenance';
		$index['b_maintenance'] = CallEntry::where('call_type', 'breakdown')->Hospital()->latest()->get();
		$index['users'] = User::pluck('name', 'id');
		return view('call_breakdowns.index', $index);
	}
	public function export($type){
		$breakdown = CallEntry::where('call_type','breakdown')->latest()->Hospital()->get();
		// dd($breakdown);
		if ($type == 'excel') {
			return Excel::download(new class($breakdown) implements FromView
            {
                public function __construct($collection)
                {
                    $this->collection = $collection;
                }

                public function view(): View
                {
                    return view('call_breakdowns.export_excel')->with('b_maintenance', $this->collection);
                }
            }, time(). '_breakdown.xlsx');
			// return Excel::download(new class ($breakdown) implements FromCollection {
			// 	public function __construct($collection)
			// 	{
			// 		$this->collection = $collection;
			// 	}
			// 	public function collection()
			// 	{
			// 		return $this->collection;
			// 	}
			// }, time() . '_breakdown.xlsx');

		} else {
			//dd($equipments);
			$pdf = PDF::loadView('call_breakdowns.export_pdf', ['breakdowns' => $breakdown])->setPaper('a4', 'landscape');
			return $pdf->download(time() . '_breakdown.pdf');
		}
        
	}
    
	public function create() {
		$this->availibility('Create Breakdown Maintenance');
		$index['page'] = 'breakdown_maintenance';
		$index['unique_ids'] = Equipment::query()->Hospital()->pluck('unique_id', 'id')->toArray();
		$index['departments'] = Department::select('id', \DB::raw('CONCAT(short_name,"(",name,")") as department'))->pluck('department', 'id')->toArray();
		$index['hospitals'] = Hospital::query()->Hospital()->pluck('name', 'id')->toArray();
		return view('call_breakdowns.create', $index);
	}

	public function store(BreakdownCreateRequest $request) {
		$breakdown = new CallEntry;
		$breakdown->call_handle = $request->call_handle;
		$breakdown->call_type = 'breakdown';
		$breakdown->equip_id = $request->equip_id;
		$breakdown->user_id = Auth::id();
		$report_no = CallEntry::where('call_handle', 'internal')->count();
		if ($breakdown->call_handle == 'external') {
			$breakdown->report_no = $request->report_no;
		} elseif ($breakdown->call_handle == 'internal') {
			$breakdown->report_no = $report_no + 1;
		}
		if (isset($request->call_register_date_time)) {
			$call_register_date_time = \Carbon\Carbon::parse($request->call_register_date_time);

			$breakdown->call_register_date_time = $call_register_date_time;
		}
		$breakdown->working_status = $request->working_status;
		$breakdown->nature_of_problem = $request->nature_of_problem;
		$breakdown->is_contamination = $request->is_contamination;
		$breakdown->save();

		$latestCallEntry = CallEntry::where('equip_id', $request->equip_id)->latest()->first();
		if ($latestCallEntry) {
			// Update the working_status of the Equipment model based on the latest CallEntry
			$equipment = Equipment::find($request->equip_id);
			if ($equipment) {
				$equipment->working_status = $latestCallEntry->working_status;
				$equipment->save();
			}
		}

		return redirect('admin/call/breakdown_maintenance')->with('flash_message', 'Breakdown Maintenance Entry created');
	}

	public function edit($id) {
		$this->availibility('Edit Breakdown Maintenance');
		$index['page'] = 'breakdown_maintenance';

		$index['breakdown'] = CallEntry::find($id);
		$index['hospitals'] = Hospital::query()->Hospital()->pluck('name', 'id')->toArray();

		$index['unique_ids'] = Equipment::query()->where('hospital_id', $index['breakdown']->equipment->hospital_id)
			->pluck('unique_id', 'id')
			->toArray();

		$h_id = $index['breakdown']->equipment->hospital_id;

		$index['departments'] = Department::select('id', \DB::raw('CONCAT(short_name,"(",name,")") as department'))
			->whereHas('equipments', function ($q) use ($h_id) {
				$q->where('hospital_id', $h_id);
			})
			->pluck('department', 'id')
			->toArray();
		return view('call_breakdowns.edit', $index);
	}

	public function update(BreakdownCreateRequest $request, $id) {
		$breakdown = CallEntry::findOrFail($id);
		$breakdown->call_handle = $request->call_handle;
		$breakdown->equip_id = $request->equip_id;

		if ($breakdown->call_handle == 'external') {
			$breakdown->report_no = $request->report_no;
		}
		if (isset($request->call_register_date_time)) {
			$call_register_date_time = \Carbon\Carbon::parse($request->call_register_date_time);
			$breakdown->call_register_date_time = $call_register_date_time;
		}
		$breakdown->working_status = $request->working_status;
		$breakdown->nature_of_problem = $request->nature_of_problem;
		$breakdown->is_contamination = $request->is_contamination;
		$breakdown->save();

		$latestCallEntry = CallEntry::where('equip_id', $request->equip_id)->latest()->first();
		if ($latestCallEntry) {
			$equipment = Equipment::find($request->equip_id);
			if ($equipment) {
				$equipment->working_status = $latestCallEntry->working_status;
				$equipment->save();
			}
		}

		return redirect('admin/call/breakdown_maintenance')->with('flash_message', 'Breakdown Maintenance Entry updated');
	}

	public function destroy($id) {
		$this->availibility('Delete Breakdown Maintenance');
		$breakdown = CallEntry::findOrFail($id);
		$breakdown->delete();
		return redirect('admin/call/breakdown_maintenance')->with('flash_message', 'Breakdown Maintenance Entry deleted');
	}

	public function ajax_unique_id(Request $request) {
		if ($request->ajax()) {
			$equipment = Equipment::where('id', $request->id)->first();
		}
		$dateConvertFormat = env('date_convert', 'd-m-Y'); // Default format if not set in .env

        // Convert date using the format from .env
        $duePmDate = $equipment->pm->next_due_date ?? '';
        $formattedDuePmDate = '';
        if ($duePmDate) {
            $formattedDuePmDate = Carbon::parse($duePmDate)->format($dateConvertFormat);
        }

        $array = $equipment->toArray();
        $array['date_pm'] = $equipment->pm->call_register_date_time ?? "";
        $array['due_pm'] = $formattedDuePmDate;
        $array['working_status'] = $equipment->working_status ?? "";
		return response()->json(['success' => $array], 200);
	}

	public function ajax_hospital_change(Request $request) {
		if ($request->ajax()) {
			$unique_id = Equipment::where('hospital_id', $request->id)
				->pluck('unique_id', 'id')
				->toArray();

			$department = Equipment::where('hospital_id', $request->id)
				->pluck('department', 'department')
				->toArray();
			$department = Department::select('id', \DB::raw('CONCAT(short_name,"(",name,")") as department'))
				->whereIn('id', $department)
				->pluck('department', 'id')->toArray();
		}
		return response()->json([
			'unique_id' => $unique_id,
			'department' => array_unique($department),

		], 200);
	}

	public function ajax_department_change(Request $request) {
		if ($request->ajax()) {
			if ($request->hospital_id && $request->hospital_id != "") {

				$unique_id = Equipment::where('department', $request->department)
					->where('hospital_id', $request->hospital_id)
					->pluck('unique_id', 'id')
					->toArray();
			} else {
				$unique_id = Equipment::where('department', $request->department)
					->pluck('unique_id', 'id')
					->toArray();
			}

		}
		return response()->json(['unique_id' => $unique_id], 200);
	}

	public function attend_call_get($id) {
		$breakdown_c = CallEntry::findOrFail($id);
		return response()->json(['b_m' => $breakdown_c->toArray()], 200);
	}
	public function attend_call(Request $request) {
		$breakdown = CallEntry::findOrFail($request->b_id);
		$validator = Validator::make($request->all(), [
			'call_attend_date_time' => 'required|after_or_equal:'.$breakdown->call_register_date_time,
			'user_attended' => 'required',
			'service_rendered' => 'required',
			'remarks' => 'required',
			'working_status' => 'required',

		],
		[
			'call_attend_date_time.after_or_equal' => 'The Call Attend Date and Time must be after or equal to  Call Register Date Time: ' . \Carbon\Carbon::parse($breakdown->call_register_date_time)->format('Y-m-d H:i:s'),
		]
	);
		if ($validator->fails()) {
			return redirect()
				->back()
				->withInput($request->all())
				->withErrors($validator, 'attend_call');
		}

		$call_attend_date_time = \Carbon\Carbon::parse($request->call_attend_date_time);
		$breakdown->call_attend_date_time = $call_attend_date_time;
		$breakdown->user_attended = $request->user_attended;
		$breakdown->service_rendered = $request->service_rendered;
		$breakdown->remarks = $request->remarks;
		$breakdown->working_status = $request->working_status;
		$breakdown->save();

		return redirect('admin/call/breakdown_maintenance')->with('flash_message', 'Breakdown Call complete details saved ');
	}

	public function call_complete_get($id) {
		$breakdown_c = CallEntry::findOrFail($id);
		return response()->json(['b_m' => $breakdown_c->toArray()], 200);
	}

	public function call_complete(Request $request) {
		$breakdown = CallEntry::findOrFail($request->b_id);

		$validator = Validator::make($request->all(), [
			'call_complete_date_time' => 'required|after_or_equal:'.$breakdown->call_attend_date_time,
			'service_rendered' => 'required',
			'remarks' => 'required',
			'working_status' => 'required',
			'sign_of_engineer' => 'mimes:jpg,jpeg,png,pdf|file|required',
			'sign_stamp_of_incharge' => 'mimes:jpg,jpeg,png,pdf|file|required',
		],
		[
			'call_complete_date_time.after_or_equal' => 'The Call Complete Date and Time must be after or equal to  Call Attend Date Time: ' . \Carbon\Carbon::parse($breakdown->call_attend_date_time)->format('Y-m-d H:i:s'),
		]
	);
		if ($validator->fails()) {
			return redirect('admin/call/breakdown_maintenance')
				->withInput($request->all())
				->withErrors($validator, 'complete_call')
				->with('breakdown_c', $breakdown);
		}

		if ($request->hasFile('sign_of_engineer')) {
			$destinationPath = 'uploads/sign_of_enginner';
			$file = $request->file('sign_of_engineer');
			$name = 'engineer' . time() . $file->getClientOriginalName();

			if (!is_null($breakdown->sign_of_engineer) && file_exists('uploads/' . $breakdown->sign_of_engineer)) {
					unlink('uploads/sign_of_enginner/'.$breakdown->sign_of_engineer);
			}
		  $file->move($destinationPath, $name);
			$breakdown->sign_of_engineer = $name;
		}
		if ($request->hasFile('sign_stamp_of_incharge')) {
			$destinationPath = 'uploads/sign_stamp_of_incharge';
			$file = $request->file('sign_stamp_of_incharge');
			$name = 'incharge' . time() . $file->getClientOriginalName();

			if (!is_null($breakdown->sign_stamp_of_incharge) && file_exists('uploads/' . $breakdown->sign_stamp_of_incharge)) {
				unlink('uploads/sign_stamp_of_incharge/'.$breakdown->sign_stamp_of_incharge);
			}
		  $file->move($destinationPath, $name);
			$breakdown->sign_stamp_of_incharge = $name;
		}

		$call_complete_date_time = Carbon::parse($request->call_complete_date_time);
		$breakdown->call_complete_date_time = $call_complete_date_time;
		$breakdown->service_rendered = $request->service_rendered;
		$breakdown->remarks = $request->remarks;
		$breakdown->working_status = $request->working_status;
		$breakdown->save();
		return redirect('admin/call/breakdown_maintenance')
			->with('flash_message', 'Breakdown Call complete details saved ');
	}

	public static function availibility($method) {
		// $r_p = Auth::user()->getPermissionsViaRoles()->pluck('name')->toArray();
		if (\Auth::user()->hasDirectPermission($method)) {
			return true;
		} else {
			abort('401');
		}
		// if (Auth::user()->hasDirectPermission($method)) {
		// 	return true;
		// } elseif (!in_array($method, $r_p)) {
		// 	abort('401');
		// } else {
		// 	return true;
		// }
	}
}
