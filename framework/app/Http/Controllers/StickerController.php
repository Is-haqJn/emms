<?php

namespace App\Http\Controllers;

use App\Calibration;
use App\Equipment;
use App\Hospital;
use \PDF;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class StickerController extends Controller
{

	public function __construct()
	{
		$this->middleware('stripEmptyParams');
	}

	public function index()
	{
		$this->availibility('View Calibration Stickers');
		$index['page'] = 'calibrations_sticker';
		$index['hospitals'] = Hospital::query()->Hospital()->pluck('name', 'id');
		$index['equipments'] = Equipment::query()->Hospital()->pluck('unique_id', 'id');
		$index['calibrations'] = Calibration::from('calibrations')
			->join(DB::raw("
        (select company, equip_id, max(date_of_calibration) as d from calibrations group by equip_id) s
    "), function ($join) {
				$join->on('calibrations.equip_id', '=', 's.equip_id');
				$join->on('calibrations.date_of_calibration', '=', 's.d');
			})
			->groupBy('calibrations.equip_id')
			->Hospital() // Apply the Hospital scope here
			->paginate(10);
		return view('stickers.calibrations_sticker', $index);
	}

	public function post(Request $request)
	{
		// dd($request->all());
		$index['page'] = 'calibrations_sticker';
		$index['hospitals'] = Hospital::query()->Hospital()->pluck('name', 'id');
		$index['hospital'] = $request->hospital;
		$index['equipments'] = Equipment::query()->Hospital()->pluck('unique_id', 'id');
		$index['unique_id'] = $request->unique_id;
		$index['working_status'] = $request->working_status;

		$calibrations = Calibration::from('calibrations')->join(
			DB::raw("
            (select company,equip_id, max(date_of_calibration) as d from calibrations group by equip_id) s
        "),
			function ($join) {
				$join->on('calibrations.equip_id', '=', 's.equip_id');
				$join->on('calibrations.date_of_calibration', '=', "s.d");
			}
		)->Hospital();

		if (isset($request->hospital)) {
			$calibrations->whereHas('equipment', function ($q) use ($request) {
				$q->where('hospital_id', $request->hospital);
			});
		}
		if (isset($request->unique_id)) {
		   // dd($calibrations->get(),$request->unique_id);
			$calibrations->where('calibrations.equip_id', $request->unique_id);
		}
		if ($request->action == "Generate Stickers") {
			$calibrations = $calibrations->groupBy('calibrations.equip_id')->get();
			if ($calibrations->count() == 0) {
				session()->flash('flash_message_error', 'There are no Calibration on this filters');
				return view('stickers.calibrations_sticker', $index);
			}
			$pdf = PDF::loadView('stickers.stickers_full', ['calibrations' => $calibrations]);
			return $pdf->download(time() . 'calibrations_sticker.pdf');
		} else {
			$index['calibrations'] = $calibrations->groupBy('calibrations.equip_id')->paginate(10)->appends(request()->only('hospital', 'unique_id'));
			//    dd($index['calibrations']);
		}
		return view('stickers.calibrations_sticker', $index);

	}
	public function single_sticker($id)
	{
		$calibration = Calibration::find($id);

		$pdf = PDF::loadView('stickers.sticker_single', ['calibration' => $calibration]);
		return $pdf->download(time() . 'calibrations_single_sticker.pdf');
	}
	public function get_equipment_ajax(Request $request)
	{
		$equipments = Equipment::where('hospital_id', $request->hospital_id)
			->select('id','unique_id as text')->get();
			// dd($equipments);
		return response()->json(['equipments' => $equipments], 200);
	}
	public function paginate($items, $perPage = 2, $page = null, $options = [])
	{
		$page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
		$items = $items instanceof Collection ? $items : Collection::make($items);
		return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
	}
	public static function availibility($method)
	{
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
