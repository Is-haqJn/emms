<?php

namespace App\Http\Controllers\Api;

use App\Calibration;
use App\CallEntry;
use App\Department;
use App\Equipment;
use App\Hospital;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\EquipmentController as EquipmentControllerWeb;
use App\Http\Requests\EquipmentRequest;
use App\QrGenerate;
use Illuminate\Support\Facades\Validator;


class EquipmentController extends Controller
{
    public function equipment(Request $request)
    {
        try {
            // dd('test')
            $data['equipments'] = Equipment::get();
            return responseData('1', 'Equipment Data', $data, 200);
        } catch (\Throwable $th) {
            return handleException($th);
        }
    }
    public function single_equipment(Request $request)
    {
        try {
            // dd($id);
            $data['equipment'] = Equipment::find($request->id);
            // dd($data['equipment']->user->name);
            $data['equipment']['user_name'] = $data['equipment']->user->name;
            $data['equipment']['department_name'] = $data['equipment']->get_department->name;
            $data['equipment']['hospital_name'] = $data['equipment']->hospital->name;
            unset($data['equipment']->user);
            unset($data['equipment']->hospital);
            unset($data['equipment']->get_department);
            return responseData('1', 'Equipment Data', $data, 200);
        } catch (\Throwable $th) {
            return handleException($th);
        }
    }
    public function department(Request $request)
    {
        try {
            $data['departments'] = Department::select('id', 'name')->get();
            return responseData('1', 'Department Data', $data, 200);
        } catch (\Throwable $th) {
            return handleException($th);
        }
    }
    public function hospital(Request $request)
    {
        try {
            $data['hospitals'] = Hospital::select('id', 'name')->get();
            return responseData('1', 'Hospital Data', $data, 200);
        } catch (\Throwable $th) {
            return handleException($th);
        }
    }
    public function qr_link(Request $request)
    {
        try {
            $path = parse_url($request->link, PHP_URL_PATH);
            // Explode the path into segments
            $segments = explode('/', $path);
            // Get the last segment (ID)
            $unique_id = end($segments);
            $data['qr'] = QrGenerate::where('uid', $unique_id)->select('id', 'uid', 'assign_to')->first();
            if (!is_null($data['qr'])) {
                if ($data['qr']->assign_to == 0) {
                    return responseData('1', 'The qr is not assigned', $data, 200);
                } else {
                    // $data['equipment'] = Equipment::find($data['qr']->assign_to);
                    return responseData('1', 'The qr is assigned', $data, 200);
                }
            } else {
                return responseData('0', 'Qr is not belong to our system', '', 401);
            }
        } catch (\Throwable $th) {
            return handleException($th);
        }
    }


    public function equipment_store(EquipmentRequest $request)
    {
        try {
            // dd('test');
            $equipment = new EquipmentControllerWeb();
            $equipment_new['equipment'] = $equipment->store_equipments_common($request, 1);
            return responseData('1', 'Equipment  Data Successfully Store', $equipment_new, 200);
        } catch (\Throwable $th) {
            return handleException($th);
        }
    }
    public function equipment_user_list(Request $request)
    {
        $user = auth('sanctum')->user();
        $data['equipments'] = Equipment::where('user_id', $user->id)->with('hospital', 'get_department')->get();
        return responseData('1', 'Equipments Fetched Successfully.', $data, 200);
    }
    public function equipment_history(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'equipment_id' => 'required',
            ]);
            $equipment_id = $request->equipment_id;
            // $data['equipment'] = Equipment::find($equipment_id);
            $data['call_entries'] = CallEntry::where('equip_id', $equipment_id)->with('equipment')->get();
            $data['calibrations'] = Calibration::where('equip_id', $equipment_id)->with('equipment')->get();
            return responseData('1', 'Equipments History Fetched Successfully.', $data, 200);
        } catch (\Throwable $th) {
            return handleException($th);
        }
    }
    public function call_entries(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            $data['call_entries'] = CallEntry::where(function($q) use ($user){
                $q->where('user_id',$user->id)->orWhere('user_attended',$user->id);
            })->with('equipment.hospital:id,address')->get();
            return responseData('1', 'Call Entry Fetched Successfully.', $data, 200);
        } catch (\Throwable $th) {
            return handleException($th);
        }
    }
    public function edit_equipment(Request $request)
    {
        try {
            $equipment = Equipment::find($request->equipment_id);
            $equipment->update([
                'company' => $request->company,
                'service_engineer_no' => $request->service_engineer_no,
                'is_critical' => $request->is_critical,
                'model' => $request->model,
                'notes' => $request->notes,
            ]);
            $data['equipment'] = $equipment;
            return responseData('1', 'Equipment Updated Successfully.', $data, 200);
        } catch (\Throwable $th) {
            return handleException($th);
        }
    }
   
}
