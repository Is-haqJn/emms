<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\CallEntry;
use App\Http\Requests\Api\CallEntryRequest;
use Carbon\Carbon; // Make sure to include the Carbon namespace if not already done

class CallEntryController extends Controller
{
    public function equipment_maintenance(CallEntryRequest $request)
    {
        $userId = auth('sanctum')->user()->id;
        $call_entry = new CallEntry;
        $call_entry->call_handle = $request->call_handle;
        $call_entry->call_type = $request->call_type;
        $call_entry->equip_id = $request->equip_id;
        $call_entry->user_id = $userId;
        $call_entry->from_api = 1;
        $report_no = CallEntry::where('call_handle', 'internal')->count();
        if ($call_entry->call_handle == 'external') {
            $call_entry->report_no = (int)$request->report_no;
        } elseif ($call_entry->call_handle == 'internal') {
            $call_entry->report_no = (int)$report_no + 1;
        }
        if (isset($request->call_register_date_time)) {
            $call_register_date_time = Carbon::parse($request->call_register_date_time);
            $call_entry->call_register_date_time = $call_register_date_time;
        }
        if (isset($request->next_due_date)) {
            $next_due_date = !empty($request->next_due_date) ? date('Y-m-d', strtotime($request->next_due_date)) : null;
            $call_entry->next_due_date = $next_due_date;
        }
        $call_entry->working_status = $request->working_status;
        $call_entry->nature_of_problem = $request->nature_of_problem;
        $call_entry->is_contamination = $request->is_contamination;
        $call_entry->save();
        $data['call_entry'] = $call_entry;
        return responseData('1', 'Equipment Maintenance Created',$data,200);
    }
    public function attend_call(Request $request){
        try{
        $userId = auth('sanctum')->user()->id;
        $call_entry = CallEntry::find($request->call_entry_id);
        $call_entry->update([
            'call_attend_date_time' => Carbon::parse($request->call_attend_date_time),
            'user_attended' => $userId,
            'service_rendered' => $request->service_rendered,
            'working_status' => $request->working_status,
            'remarks' => $request->remarks,
        ]);
        $data['call_entry'] = $call_entry;
        return responseData('1', 'Call Attend Successfully.',$data,200);
        }
        catch (\Throwable $th) {
            return handleException($th);
        }
    }
}
