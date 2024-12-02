<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipment;
use App\QrGenerate;
class QrScanController extends Controller
{
    public function index(){
        $this->availibility('View QR Scan');
        $page = 'qr-scan';
        return view('Qr_scan.index')->with(compact('page'));
    }
    public function assign_equipment($id){
        // dd('test');
        $page = 'qr-scan';
        $qr = QrGenerate::where('uid',$id)->first();
        // dd($id,$qr);
        if($qr!=null){
        $equipement = Equipment::where('qr_id',$qr->id)->first();
        if($equipement == null){
            return response()->json([
              'success'=>'not-assigned',
              'id'=>$id,
              'msg'=>'Qr Not Assigned'
            ]);
        }
        else{
            return response()->json([
                'success'=>'assigned',
                'msg'=>trans('equicare.equipment_with_this_qr_exist'),
                'url'=>url('/scan/qr/'.$qr->uid)
              ]);
        }
    }
    else{
       
        return response()->json([
            'success'=>0,
            'msg'=>'Url Is Wrong Please Try Again',
          ]);
    }
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
        //     return true;
        // } elseif (!in_array($method, $r_p)) {
        //     abort('401');
        // } else {
        //     return true;
        // }
    }
}
