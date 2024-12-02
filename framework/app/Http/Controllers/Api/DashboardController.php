<?php

namespace App\Http\Controllers\Api;

use App\CallEntry;
use App\Equipment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboardCount(Request $request){
        try {
        $user = auth('sanctum')->user();
        $data['equipmentCount'] = Equipment::where("user_id", $user->id)->count();

        $data['breakdown_pending_count'] = CallEntry::where(function($q) use ($user){
            $q->where('user_id', $user->id)->orWhere('user_attended', $user->id);
        })->where('call_complete_date_time', null)->where('call_type', 'breakdown')->count();
        
        $data['preventive_pending_count'] = CallEntry::where(function($q) use ($user){
            $q->where('user_id', $user->id)->orWhere('user_attended', $user->id);
        })->where('call_complete_date_time', null)->where('call_type', 'preventive')->count();
        
        $data['breakdown_complete_count'] = CallEntry::where(function($q) use ($user){
            $q->where('user_id', $user->id)->orWhere('user_attended', $user->id);
        })->where('call_complete_date_time', '!=', null)->where('call_type', 'breakdown')->count();
        
        $data['preventive_complete_count'] = CallEntry::where(function($q) use ($user){
            $q->where('user_id', $user->id)->orWhere('user_attended', $user->id);
        })->where('call_complete_date_time', '!=', null)->where('call_type', 'preventive')->count();
        
        return responseData('1', 'Count Fetched Successfully', $data, 200);
        
        }
        catch (\Throwable $th) {
            return handleException($th);
        }
    }

}
