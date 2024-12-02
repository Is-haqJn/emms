<?php

namespace App\Http\Controllers;

use App\Calibration;
use App\Department;
use App\Equipment;
use App\Hospital;
use App\Http\Requests\CalibrationRequest;
use App\User;
use Auth;
use DateTime;

class CalibrationController extends Controller {
	public function index() {
		$this->availibility('View Calibrations');

		$index['page'] = 'calibrations';
		$index['calibrations'] = Calibration::query()->Hospital()->get();
		$index['users'] = User::pluck('name', 'id');
		return view('calibrations.index', $index);
	}

	public function create() {
		$this->availibility('Create Calibrations');
		$index['page'] = 'calibrations';
		$index['unique_ids'] = Equipment::query()->Hospital()->pluck('unique_id', 'id')->toArray();
		$index['departments'] = Department::select('id', \DB::raw('CONCAT(short_name,"(",name,")") as department'))->pluck('department', 'id')->toArray();
		$index['hospitals'] = Hospital::query()->Hospital()->pluck('name', 'id')->toArray();
		return view('calibrations.create', $index);
	}

	public function store(CalibrationRequest $request) {
		
		$calibration = new Calibration;
		$calibration->date_of_calibration = !empty($request->date_of_calibration) ? date('Y-m-d', strtotime($request->date_of_calibration)) : null;
		$dateFormat = env('date_convert', 'Y-m-d');
        $due_date = !empty($request->due_date) ? DateTime::createFromFormat($dateFormat, $request->due_date)->format('Y-m-d') : null;
		$calibration->due_date = $due_date;
		$calibration->equip_id = $request->equip_id;
		$calibration->user_id = Auth::id();
		$calibration->certificate_no = $request->certificate_no;
		$calibration->company = $request->company_this;
		$calibration->contact_person = $request->contact_person;
		$calibration->contact_person_no = $request->contact_person_no;
		$calibration->engineer_no = $request->engineer_no;
		$calibration->traceability_certificate_no = $request->traceability_certificate_no;

		if(isset($request->calibration_certificate)){
			$imageName = time() . '.' . request()->calibration_certificate->getClientOriginalExtension();
      request()->calibration_certificate->move('uploads/certificates/', $imageName);
      $calibration->calibration_certificate = 'uploads/certificates/' . $imageName;
		}
		$calibration->save();

		return redirect('admin/calibration')->with('flash_message', 'Calibration created');
	}

	public function edit($id) {
		$this->availibility('Edit Calibrations');
		$index['page'] = 'calibrations';
		$index['calibration'] = Calibration::findOrFail($id);
		$index['hospitals'] = Hospital::query()->Hospital()->pluck('name', 'id')->toArray();

		$index['unique_ids'] = Equipment::where('hospital_id', $index['calibration']->equipment->hospital_id)
			->pluck('unique_id', 'id')
			->toArray();

		$index['departments'] = Department::select('id', \DB::raw('CONCAT(short_name,"(",name,")") as department'))->pluck('department', 'id')->toArray();
		return view('calibrations.edit', $index);
	}

	public function update(CalibrationRequest $request, $id) {
		$calibration = Calibration::findOrFail($id);
		$calibration->date_of_calibration = !empty($request->date_of_calibration) ? date('Y-m-d', strtotime($request->date_of_calibration)) : null;
		$dateFormat = env('date_convert', 'Y-m-d');
        $due_date = !empty($request->due_date) ? DateTime::createFromFormat($dateFormat, $request->due_date)->format('Y-m-d') : null;
		$calibration->due_date = $due_date;
		$calibration->equip_id = $request->unique_id;
		$calibration->certificate_no = $request->certificate_no;
		$calibration->company = $request->company;
		$calibration->contact_person = $request->contact_person;
		$calibration->contact_person_no = $request->contact_person_no;
		$calibration->engineer_no = $request->engineer_no;
		$calibration->traceability_certificate_no = $request->traceability_certificate_no;

		if(isset($request->calibration_certificate)){
			if(isset($calibration->calibration_certificate)){
				unlink($calibration->calibration_certificate);
			}
			$imageName = time() . '.' . request()->calibration_certificate->getClientOriginalExtension();
      request()->calibration_certificate->move('uploads/certificates/', $imageName);
      $calibration->calibration_certificate = 'uploads/certificates/' . $imageName;
		}
		$calibration->save();

		return redirect('admin/calibration')->with('flash_message', 'Calibration updated	');
	}

	public function destroy($id) {
		$this->availibility('Delete Calibrations');
		$calibration = Calibration::findOrFail($id);
		$calibration->delete();
		return redirect('admin/calibration')->with('flash_message', 'Calibration deleted	');
	}
	public static function availibility($method) {
		// $r_p = \Auth::user()->getPermissionsViaRoles()->pluck('name')->toArray();
		if (\Auth::user()->hasDirectPermission($method)) {
			return true;
		} else {
			abort('401');
		}
		// if (\Auth::user()->hasDirectPermission($method)) {
		// 	return true;
		// } elseif (!in_array($method, $r_p)) {
		// 	abort('401');
		// } else {
		// 	return true;
		// }
	}
}
