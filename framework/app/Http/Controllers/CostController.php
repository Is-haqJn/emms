<?php

namespace App\Http\Controllers;

use App\Cost;
use App\Equipment;
use App\Hospital;
use App\Http\Requests\CreateCostRequest;
use Illuminate\Http\Request;

class CostController extends Controller {
	public function index() {
		$this->availibility('View Maintenance Cost');
		$index['page'] = 'maintenance_cost';
		$index['maintenance_costs'] = Cost::query()->Hospital()->get();

		return view('maintenance_cost.index', $index);
	}

	public function create() {
		$this->availibility('Create Maintenance Cost');
		$index['page'] = 'maintenance_cost';
		$index['hospitals'] = Hospital::query()->Hospital()->pluck('name', 'id');

		return view('maintenance_cost.create', $index);
	}

	public function store(CreateCostRequest $request) {
		$cost = new Cost;
		$cost->hospital_id = $request->hospital_id;
		$cost->type = $request->type;
		$cost->cost_by = $request->cost_by;
		if ($request->cost_by == 'tp') {
			$cost->tp_name = $request->tp_name;
			$cost->tp_email = $request->tp_email;
			$cost->tp_mobile = $request->tp_mobile;
		}
		$cost->equipment_ids = json_encode($request->equipments);
		// dd(json_encode($request->start_dates));
		$cost->start_dates = json_encode($request->start_dates);
		// dd(!empty($request->start_dates) ? date('Y-m-d', strtotime(json_encode($request->start_dates))) : null);
		//  $cost->start_dates = !empty($request->start_dates) ? date('Y-m-d', strtotime(json_encode($request->start_dates))) : null;
		$cost->end_dates = json_encode($request->end_dates);
		//  $cost->end_dates = !empty($request->end_dates) ? date('Y-m-d', strtotime(json_encode($request->end_dates))) : null;
		$cost->costs = json_encode($request->cost);
		$cost->save();
		return redirect('admin/maintenance_cost')->with('flash_message', 'Maintenance Cost Data created');
	}

	public function edit(Cost $maintenance_cost) {
		$page = 'maintenance_cost';

		$hospitals = Hospital::query()->Hospital()->pluck('name', 'id');
		$equipments = Equipment::where('hospital_id', $maintenance_cost->hospital_id)->pluck('unique_id', 'id')->toArray();
		return view('maintenance_cost.edit', compact('maintenance_cost', 'page', 'equipments', 'hospitals'));
	}

	public function update(CreateCostRequest $request, Cost $maintenance_cost) {
		$cost = $maintenance_cost;
		$cost->hospital_id = $request->hospital_id;
		$cost->type = $request->type;
		$cost->cost_by = $request->cost_by;
		if ($request->cost_by == 'tp') {
			$cost->tp_name = $request->tp_name;
			$cost->tp_email = $request->tp_email;
			$cost->tp_mobile = $request->tp_mobile;
		}
		
		$cost->equipment_ids = json_encode($request->equipments);
		$cost->start_dates = json_encode($request->start_dates);
		$cost->end_dates = json_encode($request->end_dates);
		// $cost->start_dates = !empty($request->start_dates) ? date('Y-m-d', strtotime(json_encode($request->start_dates))) : null;
		//  $cost->end_dates = !empty($request->end_dates) ? date('Y-m-d', strtotime(json_encode($request->end_dates))) : null;
		$cost->costs = json_encode($request->cost);
		$cost->save();
		return redirect('admin/maintenance_cost')->with('flash_message', 'Maintenance Cost Data updated');
	}

	public function destroy(Cost $maintenance_cost) {
		$maintenance_cost->delete();
		return redirect('admin/maintenance_cost')->with('flash_message', 'Maintenance Cost Data Deleted');
	}

	public function get_equipment(Request $request) {

		$equipments = Equipment::where('hospital_id', $request->hospital_id)->pluck('unique_id', 'id')->toArray();

		return response()->json(['equipments' => $equipments], 200);
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
	public function get_info(Request $request) {
		$data['cost'] = Cost::find($request->id);
		if (is_null($data['cost'])) {
			echo "not_exist";
			exit;
		}
		$data['decoded_ids'] = json_decode($data['cost']->equipment_ids);
		$data['decoded_start_date'] = json_decode($data['cost']->start_dates);
		$data['decoded_end_dates'] = json_decode($data['cost']->end_dates);
		$data['decoded_costs'] = json_decode($data['cost']->costs);
		return view('maintenance_cost.get_info', $data);
	}
}
