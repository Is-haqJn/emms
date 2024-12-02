<?php

namespace App\Http\Controllers;
use QrCode;
use App\QrGenerate;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class QrController extends Controller
{
    public function create(Request $request){
          $data['page'] = 'qr';
          return view('Qr.create')->with($data);
        }
        public function qr_assigned(){
          $this->availibility('View QR Generate');
          $data['page'] = 'qr';
          $data['qr']= QrGenerate::where('assign_to','!=',0)->latest()->get();
          return view('Qr.assigned')->with($data);
        }
         public function index(Request $request){
            $this->availibility('View QR Generate');
            $data['page'] = 'qr';
            // QrGenerate::select('*')->delete();
            $data['qr']=QrGenerate::where('assign_to',0)->latest()->get();
            return view('Qr.index')->with($data);
          }
    public function qr_generate(Request $request){
    $count = $request->count;

    $validator = Validator::make($request->all(), [
          'count' => [
            'required',
            'integer',
          ],
        ]);
        if ($validator->fails() || $count > 100) {
          if ($count > 100) {
            $validator->errors()->add('count', 'Count must not exceed 100.'); 
          }
          return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        for ($i = 1; $i <= $count; $i++) {
            $qrcode = new QrGenerate;
            $qrcode->assign_to = 0;
            $qrcode->uid = Str::random(11);
            $qrcode->save();
            $url = url('/') . "/scan/qr/".$qrcode->uid;
            QrCode::format('png')->size(300)->generate($url, 'uploads/qrcodes/qr_assign/' . $qrcode->uid . '.png');
          }
          return redirect()->route('qr.index')
          ->with('flash_message','Qr Generated Successfully');
        }
     public function destroy($id)
     {
        $data['qr'] = QrGenerate::find($id)->delete();
        return redirect()->back()
        ->with('flash_message','Qr Deleted Successfully');
     }
     public function qr_sticker($type,Request $request){
      // dd($type);
      //  dd($request->all());
      if($type=='not-assigned'){
        $qr = QrGenerate::where('assign_to',0)->get();
        // dd($qr);
       }
       else{
        $qr = QrGenerate::where('assign_to','!=',0)->get();
       }
      $pdf = PDF::loadView('Qr.sticker', ['qr' => $qr,'size'=>$request->qr_size,'qr_line'=>$request->qr_line]);
			return $pdf->stream(time() . 'qr_sticker.pdf');
     }
  public static function availibility($method)
  {
    if (\Auth::user()->hasDirectPermission($method)) {
      return true;
    } else {
      abort('401');
    }
  }
    
}
