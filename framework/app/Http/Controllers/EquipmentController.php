<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use Excel;
use QrCode;
use App\Hospital;
use App\CallEntry;
use App\Equipment;
use App\Department;
use App\Calibration;
use Illuminate\Http\Request;
// use App\Role;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Spaite\Permission\Role;
use App\QrGenerate;
use App\ExportBackup;
use App\EquipDocs;
use App\EquipmentImages;
use Illuminate\Support\Collection;
use App\Http\Requests\EquipmentRequest;
use App\Http\Requests\ExportREquest;
use App\Http\Requests\DocumentRequest;
use App\Exports\EquipmentExcelExport;

// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use DateTime;
// use Maatwebsite\Excel\Facades\Excel;
class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Artisan::call('migrate:fresh',['--force' => true]);
        // Artisan::call('db:seed',['--force' => true]);
        set_time_limit(0);
        $this->availibility('View Equipments');
        $index['page'] = 'equipments';
        //$index['equipments'] = Equipment::latest()->get();
        $index['hospitals'] = Hospital::query()->Hospital()->get();
        $index['departments'] = Department::get();
        $index['companies'] = Equipment::query()->Hospital()->distinct()->get(['company']);
        $index['hospital_id'] = isset($request->hospital_id) ? $request->hospital_id : "";
        $index['companyy'] = isset($request->company) ? $request->company : "";
        $index['department_id'] = $request->input('department_id', "");
        $index['exportbackup'] = ExportBackup::where('type', 'excel')->first();
        $index['exportpdfbackup'] = ExportBackup::where('type','pdf')->first();
        $index['columns'] = [
            'name' => 'Name',
            'short_name' => 'Short Name',
            'user_id' => 'User',
            'company' => 'Company',
            'model' => 'Model',
            'hospital_id' => 'Hospital',
            'sr_no' => 'Serial No',
            'department' => 'Department',
            'unique_id' => 'Unique Id',
            'date_of_purchase' => 'Purchase Date',
            'order_date' => 'Order Date',
            'date_of_installation' => 'Installation Date',
            'warranty_due_date' => 'Warranty Due Date',
        ];
        $index['columns_pdf'] = [
            'name' => 'Name',
            'qr_id' => 'Qr',
            'short_name' => 'Short Name',
            'user_id' => 'User',
            'company' => 'Company',
            'model' => 'Model',
            'hospital_id' => 'Hospital',
            'sr_no' => 'Serial No',
            'department' => 'Department',
            'unique_id' => 'Unique Id',
            'date_of_purchase' => 'Purchase Date',
            'order_date' => 'Order Date',
            'date_of_installation' => 'Installation Date',
            'warranty_due_date' => 'Warranty Due Date',
        ];
        
        $equipments = Equipment::select('*')->Hospital();
        if (isset($index['hospital_id']) && $index['hospital_id'] != "") {
            $equipments->where('hospital_id', $index['hospital_id']);
        }
        if ($index['department_id']) { // New filter for department
            $equipments->where('department', $index['department_id']);
        }
        if (isset($index['companyy']) && $index['companyy'] != "") {
            $equipments->where('company', $index['companyy']);
        }
        if (isset($request->excel_hidden)) {
            $equipments = $equipments->latest()->get();
            $updatedEquipments = [];
            foreach ($equipments as $equipment) {
                $equipment->date_of_purchase = date_change($equipment->date_of_purchase);
                $equipment->order_date = date_change($equipment->order_date);
                $equipment->date_of_installation = date_change($equipment->date_of_installation);
                $equipment->warranty_due_date = date_change($equipment->warranty_due_date);
                $updatedEquipments[] = $equipment;
            }
            $equipments = collect($updatedEquipments);
            // return Excel::download(new class($equipments) implements FromView
            // {
            //     public function __construct($collection)
            //     {
            //         $this->collection = $collection;
            //     }

            //     public function view(): View
            //     {
                //         return view('equipments.export_excel')->with('equipments', $this->collection);
                //     }
                // }, time(). '_equipment.xlsx');
            } elseif (isset($request->pdf_hidden)) {

                $equipments = $equipments->latest()->get();
                //dd($equipments);
                $pdf = PDF::loadView('equipments.export_pdf', ['equipments' => $equipments])->setPaper('a4', 'landscape');
                return $pdf->download(time() . '_equipment.pdf');
            } else {
                $index['equipments'] = $equipments->latest()->get();
                // dd($index['equipments']);
        }
        return view('equipments.index', $index);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $this->availibility('Create Equipments');
        $index['page'] = 'equipments';
        $index['hospitals'] = Hospital::query()->Hospital()->get();
        $index['departments'] =
            Department::select('id', DB::raw('CONCAT(short_name," (" , name ,")") as full_name'))
                ->pluck('full_name', 'id')
                ->toArray();
        return view('equipments.create', $index);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_equipments_common(Request $request,$api=0)
    {
        $equipment = new Equipment;
        $equipment->name = trim($request->name);
        $equipment->short_name = $request->short_name;
        if($api==1){
            $equipment->user_id = auth('sanctum')->user()->id;
        } else {
            $equipment->user_id = \Auth::user()->id;
        }
        $equipment->company = $request->company;
        $equipment->sr_no = $request->sr_no;
        $equipment->hospital_id = $request->hospital_id;
        $equipment->department = $request->department;
        $equipment->model = $request->model;
        $equipment->qr_id = $request->qr_id;
        $equipment->working_status = $request->working_status;


        $dateFormat = env('date_convert', 'Y-m-d');
        $date_of_purchase = !empty($request->date_of_purchase) ? DateTime::createFromFormat($dateFormat, $request->date_of_purchase)->format('Y-m-d') : null;
        $order_date = !empty($request->order_date) ? DateTime::createFromFormat($dateFormat, $request->order_date)->format('Y-m-d') : null;
        $date_of_installation = !empty($request->date_of_installation) ? DateTime::createFromFormat($dateFormat, $request->date_of_installation)->format('Y-m-d') : null;
        $warranty_due_date = !empty($request->warranty_due_date) ? DateTime::createFromFormat($dateFormat, $request->warranty_due_date)->format('Y-m-d') : null;

        // $date_of_purchase = !empty($request->date_of_purchase) ?\Carbon\Carbon::createFromFormat('m-d-Y',$request->date_of_purchase) : null;
        // $order_date = !empty($request->order_date) ?\Carbon\Carbon::createFromFormat('m-d-Y',$request->order_date) : null;
        // $date_of_installation = !empty($request->date_of_installation) ?\Carbon\Carbon::createFromFormat('m-d-Y',$request->date_of_installation) : null;
        // $warranty_due_date = !empty($request->warranty_due_date) ?\Carbon\Carbon::createFromFormat('m-d-Y',$request->warranty_due_date) : null;

        $equipment->date_of_purchase = $date_of_purchase;
        $equipment->order_date = $order_date;
        $equipment->date_of_installation = $date_of_installation;
        $equipment->warranty_due_date = $warranty_due_date;
        $equipment->service_engineer_no = $request->service_engineer_no;
        $equipment->is_critical = $request->is_critical;
        $equipment->notes = $request->notes;
        $equipment_number = Equipment::where('hospital_id', $request->hospital_id)
            ->where('name', trim($request->name))
            ->where('short_name', $request->short_name)
            ->where('department', $request->department)
            ->count();
        $equipment_number = sprintf("%02d", $equipment_number + 1);
        $equipment->unique_id = "";
        $hospital = Hospital::where('id', $request->hospital_id)->first();
        if ($hospital != "") 
        {
            $unique_id = $hospital->slug . '/' . $equipment->department . '/' . $equipment->short_name . '/' . $equipment_number;
			$label_name=$hospital->slug . '/' . $equipment->department . '/' . $equipment->short_name . '/';
			$equipment_last = Equipment::where('unique_id', 'like', $label_name.'%')->orderBy('unique_id', 'desc')->first();
			if($equipment_last){
				$last_label_no=explode('/',$equipment_last->unique_id);
				$last_label_no=end($last_label_no);
				$equipment_number = sprintf("%02d", ((int)$last_label_no) + 1);
				$unique_id=$label_name.$equipment_number;
			}else{
				$unique_id=$label_name."01";
			}
            $equipment->unique_id = $unique_id;
        }
        $equipment->save();

        $id = $equipment->id;
        //for generating qr 
        $equipment = Equipment::find($id);
        //update equipment qr_id if it not coming from request
        // dd($request->qr_id); 
        if (request('qr_id') != null) {
            $qr = QrGenerate::where('uid', request('qr_id'))->first();
            $qr->assign_to = $equipment->id;
            $qr->save();
            $equipment->qr_id = $qr->id;
            $equipment->save();
        } else {
            $qr = new QrGenerate;
            $qr->assign_to = $equipment->id;
            $qr->uid = Str::random(11);
            $qr->save();
            $url = url('/') . "/scan/qr/" . $qr->uid;
            if (extension_loaded('imagick')) {
            QrCode::format('png')->size(300)->generate($url, 'uploads/qrcodes/qr_assign/' . $qr->uid . '.png');
            }
            $equipment->qr_id = $qr->id;
            $equipment->save();
        }
        // dd('test');
        return $equipment;
    }
    public function store(EquipmentRequest $request)
    {
        $equipment = $this->store_equipments_common($request,0);
        return redirect('admin/equipments')->with('flash_message', 'Equipment "' . $equipment->name . '" created');
    }

    public function regenerate_all_qr()
    {
        set_time_limit(0);
        $equipments = Equipment::all();
        foreach ($equipments as $key => $equipment) {
            $id = $equipment->id;
            if (extension_loaded('imagick')) {
                // Generate QR Code
                $url = url('/') . "/equipments/history/" . $id;
                $image = QrCode::format('png')->size(300)->generate($url, 'uploads/qrcodes/' . $id . '.png');
            }
        }
        echo "QRs regenerated!";
    }

    public function qr($id)
    {
        $equipment = Equipment::findOrFail($id);
        $url = url('/') . "/admin/equipments/history/" . $equipment->id;
        $qr_content = __('equicare.equipment_id') . ": " . $equipment->unique_id . " \n\n" .
            __('equicare.details') . ": " . $url;
        return '<div style="text-align:center;"><img src="data:image/png;base64, ' . base64_encode(QrCode::format('png')->size(150)->generate($qr_content)) . '"></div>';
    }

    public function qr_image($id)
    {
        $equipment = Equipment::findOrFail($id);
        $url = url('/') . "/admin/equipments/history/" . $equipment->id;
        $qr_content = __('equicare.equipment_id') . ": " . $equipment->unique_id . " \n\n" .
            __('equicare.details') . ": " . $url;
        $image = QrCode::format('png')->size(150)->generate($qr_content);
        return response($image)->header('Content-type', 'image/png');
    }

    public function edit($id)
    {
        $this->availibility('Edit Equipments');
        $index['page'] = 'equipments';
        $index['equipment'] = Equipment::findOrFail($id);
        $index['hospitals'] = Hospital::query()->Hospital()->get();
        $index['departments'] =
            Department::select('id', DB::raw('CONCAT(short_name," (" , name ,")") as full_name'))
                ->pluck('full_name', 'id')
                ->toArray();
        return view('equipments.edit', $index);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\quipments  $quipments
     * @return \Illuminate\Http\Response
     */
    public function update(EquipmentRequest $request, $id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->name = trim($request->name);
        $equipment->short_name = $request->short_name;
        $equipment->user_id = \Auth::user()->id;
        $equipment->company = $request->company;
        $equipment->sr_no = $request->sr_no;
        $equipment->hospital_id = $request->hospital_id;
        $equipment->department = $request->department;
        $equipment->model = $request->model;

        // $date_of_purchase = !empty($request->date_of_purchase) ?\Carbon\Carbon::createFromFormat('m-d-Y',$request->date_of_purchase) : null;
        // $order_date = !empty($request->order_date) ?\Carbon\Carbon::createFromFormat('m-d-Y',$request->order_date) : null;
        // $date_of_installation = !empty($request->date_of_installation) ?\Carbon\Carbon::createFromFormat('m-d-Y',$request->date_of_installation) : null;
        // $warranty_due_date = !empty($request->warranty_due_date) ?\Carbon\Carbon::createFromFormat('m-d-Y',$request->warranty_due_date) : null;
        $dateFormat = env('date_convert', 'Y-m-d');
        $date_of_purchase = !empty($request->date_of_purchase) ? DateTime::createFromFormat($dateFormat, $request->date_of_purchase)->format('Y-m-d') : null;
        $order_date = !empty($request->order_date) ? DateTime::createFromFormat($dateFormat, $request->order_date)->format('Y-m-d') : null;
        $date_of_installation = !empty($request->date_of_installation) ? DateTime::createFromFormat($dateFormat, $request->date_of_installation)->format('Y-m-d') : null;
        $warranty_due_date = !empty($request->warranty_due_date) ? DateTime::createFromFormat($dateFormat, $request->warranty_due_date)->format('Y-m-d') : null;

        // $date_of_purchase = !empty($request->date_of_purchase) ? date('Y-m-d', strtotime($request->date_of_purchase)) : null;
        // $order_date = !empty($request->order_date) ? date('Y-m-d', strtotime($request->order_date)) : null;
        // $date_of_installation = !empty($request->date_of_installation) ? date('Y-m-d', strtotime($request->date_of_installation)) : null;
        // $warranty_due_date = !empty($request->warranty_due_date) ? date('Y-m-d', strtotime($request->warranty_due_date)) : null;

        $equipment->date_of_purchase = $date_of_purchase;
        $equipment->order_date = $order_date;
        $equipment->date_of_installation = $date_of_installation;
        $equipment->warranty_due_date = $warranty_due_date;
        $equipment->service_engineer_no = $request->service_engineer_no;
        $equipment->is_critical = $request->is_critical;
        $equipment->notes = $request->notes;
        $equipment->working_status = $request->working_status;


        $equipment->save();
        // if (extension_loaded('imagick')) {
        //     // Generate QR Code
        //     $url = url('/') . "/equipments/history/" . $id;
        //     $image = QrCode::format('png')->size(300)->generate($url, 'uploads/qrcodes/' . $id . '.png');
        // }

        return redirect('admin/equipments')->with('flash_message', 'Equipment "' . $equipment->name . '" updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\quipments  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->availibility('Delete Equipments');
        $equipment = Equipment::findOrFail($id);
        $qr = QrGenerate::find($equipment->qr_id);
        if(!$qr){
        return redirect('admin/equipments')->with('');

        }
        $qr->delete();
        $equipment->delete();

        return redirect('admin/equipments')->with('flash_message', 'Equipment "' . $equipment->name . '" deleted');
    }

    public static function availibility($method)
    {
        if (\Auth::user()->hasDirectPermission($method)) {
            return true;
        } else {
            abort('401');
        }

        // $r_p = \Auth::user()->getPermissionsViaRoles()->pluck('name')->toArray();

        // if (\Auth::user()->hasDirectPermission($method)) {

        //     return true;
        // } elseif (!in_array($method, $r_p)) {
        //     abort('401');
        // } else {
        //     return true;
        // }
    }

    public function history($id)
    {
        // dd($id,$request->all());
        $index['page'] = 'equipments history';
        $index['equipment'] = Equipment::find($id);
        $index['equip_img'] = EquipmentImages::where('equip_id', $id)->first(); 
        $history = collect();
        $h1 = CallEntry::where('equip_id', $id)->with('user')->with('user_attended_fn')->get();
        foreach ($h1 as $h) {
            $h2 = collect($h);
            $h2->put('type', 'Call');
            $history[] = $h2;
        }

        $calibration = collect();
        $c1 = Calibration::where('equip_id', $id)->with('user')->get();
        foreach ($c1 as $c) {
            $c2 = collect($c);
            $c2->put('type', 'Calibration');
            $calibration[] = $c2;
        }

        $collection = new Collection();
        $index['data'] = $collection->merge($history)->merge($calibration)->sortByDesc('created_at');

        return view('equipments.history', $index);
    }
    public function history_qr($uid)
    {
        // dd("wdef");
        $index['page'] = 'equipments history';
        $qr = QrGenerate::where('uid', $uid)->first();
        // dd($qr);
        // if($qr->assing_to !=0){
        $ui = Equipment::find($qr->assign_to);
        if(!$ui == null){
        $index['equip_img'] = EquipmentImages::where('equip_id', $ui->id)->first(); 
        }
        if ($qr->assign_to != 0) {
            $index['equipment'] = Equipment::find($qr->assign_to);
            $id = $index['equipment']->id;
            $history = collect();
            $h1 = CallEntry::where('equip_id', $id)->with('user')->with('user_attended_fn')->get();
            foreach ($h1 as $h) {
                $h2 = collect($h);
                $h2->put('type', 'Call');
                $history[] = $h2;
            }

            $calibration = collect();
            $c1 = Calibration::where('equip_id', $id)->with('user')->get();
            foreach ($c1 as $c) {
                $c2 = collect($c);
                $c2->put('type', 'Calibration');
                $calibration[] = $c2;
            }

            $collection = new Collection();
            $index['data'] = $collection->merge($history)->merge($calibration)->sortByDesc('created_at');

            return view('equipments.history', $index);
        } else {
            return redirect('admin/equipments/create' . '?qr_id=' . $qr->uid);
        }
    }
    public function exportToExcel(ExportREquest $request)
    {
        $request->validate([
            'columns' => 'required|array|min:1',
            'title' => 'required|string',
            'subtitle' => 'required|string',
        ],[
            'columns.required' => 'Please select at least one column.',
            'columns.min' => 'Please select at least one column.',
        ]);
        
        $columns = $request->input('columns');
        $title = $request->input('title');
        $subtitle = $request->input('subtitle');

        $excel = ExportBackup::where('type','excel')->first();
        if($excel)
        {
            $excel->title = $title;
            $excel->subtitle = $subtitle;
            $excel->columns = json_encode($columns);
            $excel->save();
        }else{
            $exportexcel = new ExportBackup;
            $exportexcel->type = "excel";
            $exportexcel->title = $title;
            $exportexcel->subtitle = $subtitle;
            $exportexcel->columns = json_encode($columns);
            $exportexcel->save();
        }
        $equipments = Equipment::with(['user', 'hospital', 'get_department'])
                       ->select($columns)
                       ->orderBy('created_at', 'desc')
                       ->Hospital()
                       ->get();

        return Excel::download(new class($equipments, $columns, $title, $subtitle) implements FromView {
            protected $equipments;
            protected $columns;
            protected $title;
            protected $subtitle;

        public function __construct($equipments, $columns, $title, $subtitle)
        {
            $this->equipments = $equipments;
            $this->columns = $columns;
            $this->title = $title;
            $this->subtitle = $subtitle;
        }

        public function view(): \Illuminate\Contracts\View\View
        {
            return view('equipments.export_excel', [
                'data' => $this->equipments,
                'columns' => $this->columns,
                'title' => $this->title,
                'subtitle' => $this->subtitle,
            ]);
         }

         public function styles(Worksheet $sheet)
        {
            $sheet->mergeCells('A1:' . $sheet->getHighestColumn() . '1');
            $sheet->mergeCells('A2:' . $sheet->getHighestColumn() . '2');

            $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
        }
        
        }, 'equipment.xlsx');
     }

     public function exportToPdf(Request $request)
     {
        $request->validate([
            'columns' => 'required|array|min:1',
            'title' => 'required|string',
            'subtitle' => 'required|string',
        ], [
            'columns.required' => 'Please select at least one column.',
            'columns.min' => 'Please select at least one column.',
        ]);
         $columns = $request->input('columns');
         $title = $request->input('title');
         $subtitle = $request->input('subtitle');
     
         $excel = ExportBackup::where('type', 'pdf')->first();
         if ($excel) {
             $excel->title = $title;
             $excel->subtitle = $subtitle;
             $excel->columns = json_encode($columns);
             $excel->save();
         } else {
             $exportexcel = new ExportBackup;
             $exportexcel->type = "pdf";
             $exportexcel->title = $title;
             $exportexcel->subtitle = $subtitle;
             $exportexcel->columns = json_encode($columns);
             $exportexcel->save();
         }
     
         $equipments = Equipment::with(['user', 'hospital', 'get_department'])
             ->select($columns)
             ->orderBy('created_at', 'desc')
             ->Hospital()
             ->get();

            if($equipments === null){
                return redirect()->back()->withError('pls check at least one column');
            }
     
         $pdf = PDF::loadView('equipments.export_pdf', [
             'data' => $equipments,
             'columns' => $columns,
             'title' => $title,
             'subtitle' => $subtitle,
         ])->setPaper('a4', 'landscape');
     
         return $pdf->download(time() . '_equipment.pdf');
     }
     public function deleteImage(Request $request)
         {
            // dd($request->all());
             $imageId = $request->input('image_id');
             $imageIndex = $request->input('image_index');
             $image = EquipmentImages::find($imageId);
            //  dd($image);
         
             if ($image) {
                 $multipleImages = json_decode($image->multiple_images, true);
                 if (isset($multipleImages[$imageIndex])) {
                    //  dd("12");
                     $imagePath = 'uploads/EquipImages/' . $multipleImages[$imageIndex];
                    
                     if (file_exists($imagePath)) {
                         unlink($imagePath);
                     }
         
                     unset($multipleImages[$imageIndex]);
                     
                     $image->multiple_images = json_encode($multipleImages);
                     $image->save();
         
                     return response()->json(['status' => 'success']);
                 }
             }
         
             return response()->json(['status' => 'error'], 400);
         }

         public function deleteThumbnailImage(Request $request, $id)
        {
            // if($request->ajax()){
            //     dd("Fdf");
            // };
            $image = EquipmentImages::where('equip_id', $id)->first();

            if ($image && $image->thumbnail_image) {
            $imagePath = 'uploads/EquipImages/' . $image->thumbnail_image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $image->thumbnail_image = null;
            $image->save();
            }
            return response()->json(['status' => 'success']);
        }

        public function upload_document($id, DocumentRequest $request) {
         
            $request->validate([
                'document_name' => 'required|string|max:255',
                'document_file' => 'required|mimes:pdf,doc,docx,xls,xlsx|max:2048',
            ]);

    
            if ($request->hasFile('document_file')) {
                $file = $request->file('document_file');
                $fileName = time() . '_document_' . $file->getClientOriginalName();
        
                $destinationPath = 'uploads/documents';
             
                $equipment = new EquipDocs();
                $equipment->equip_id = $id;
        
                if ($equipment->document_file && file_exists($destinationPath . '/' . $equipment->document_file)) {
                    unlink($destinationPath . '/' . $equipment->document_file);
                }
        
                $file->move($destinationPath, $fileName);
        
                $equipment->document_name = $request->input('document_name');
                $equipment->document_file = $fileName;
                $equipment->user_id = auth()->user()->id;
        
                $equipment->save();
    
                return response()->json([
                    'document_name' => $equipment->document_name,
                    'document_file' => $equipment->document_file,
                    'document_url' => asset('uploads/documents/' . $equipment->document_file),
                    'user_name' => ucfirst(auth()->user()->name),
                    'uploaded_time' => $equipment->created_at->format('Y-m-d H:i:s'),
                    'document_id' => $equipment->id,
                ]);
        
            } else {
                return response()->json(['error' => 'No file uploaded'], 400);
            }
        }
        public function delete_document($id, Request $request)
        {
            $equipment = EquipDocs::find($id);
            if ($equipment) {
                if ($equipment->document_file) {
                    $filePath = public_path($equipment->document_file);
                    if (file_exists($filePath)) {
                        unlink($filePath); 
                    }
                }
                $equipment->delete();
                return response()->json(['success' => true], 200);
            }
            return response()->json(['success' => false], 404);
        }
    
        public function upload_images($id, Request $request)
        {
            // dd($request->all()); 
            $equipment = Equipment::find($id);
            if ($equipment) {
                $equipimage = EquipmentImages::where('equip_id', $equipment->id)->first();
                $destinationPath = 'uploads/EquipImages';
        
                if ($equipimage) {
                    $thumbnailImage = $request->file('thumbnail_image');
                    if ($thumbnailImage && $thumbnailImage->isValid()) {
                        $thumbnailImageName = uniqid() . '.' . $thumbnailImage->getClientOriginalExtension();
                        $thumbnailImage->move($destinationPath, $thumbnailImageName);
                        $equipimage->thumbnail_image = $thumbnailImageName;
                    } else {
                        $thumbnailImageName = $equipimage->thumbnail_image;
                    }
        
                    $multipleImages = $request->file('multiple_images');
                       
                    $existingImages = json_decode($equipimage->multiple_images, true);
        
                    if ($multipleImages && is_array($multipleImages)) {
                        $imageNames = $existingImages ?? [];
        
                        foreach ($multipleImages as $image) {
                            if ($image && $image->isValid()) {
                                $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                                $image->move($destinationPath, $imageName);
                                $imageNames[] = $imageName;
                            }
                        }
                        $equipimage->multiple_images = json_encode($imageNames);
                    } else {
                        $imageNames = $existingImages ?? [];
                    }
                    $equipimage->save();
        
                    return response()->json([
                        'success' => 'Images saved successfully',
                        'thumbnail_image' => $thumbnailImageName,
                        'multiple_images' => $imageNames,
                        'equip_id' => $equipimage->id
                    ]);
                } else {
                    $thumbnailImage = $request->file('thumbnail_image');
                    if ($thumbnailImage && $thumbnailImage->isValid()) {
                        $thumbnailImageName = uniqid() . '.' . $thumbnailImage->getClientOriginalExtension();
                        $thumbnailImage->move($destinationPath, $thumbnailImageName);
                    } else {
                        $thumbnailImageName = null;
                    }
        
                    $multipleImages = $request->file('multiple_images');
                    $imageNames = [];
                    if ($multipleImages && is_array($multipleImages)) {
                        foreach ($multipleImages as $image) {
                            if ($image && $image->isValid()) {
                                $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                                $image->move($destinationPath, $imageName);
                                $imageNames[] = $imageName;
                            }
                        }
                    }
        
                    $equipmentImage = new EquipmentImages();
                    $equipmentImage->equip_id = $id;
                    $equipmentImage->thumbnail_image = $thumbnailImageName;
                    $equipmentImage->multiple_images = json_encode($imageNames);
                    $equipmentImage->save();
        
                    return response()->json([
                        'success' => 'Images saved successfully',
                        'thumbnail_image' => $thumbnailImageName,
                        'multiple_images' => $imageNames,
                        'equip_id' => $equipmentImage->id
                    ]);
                }
            } else {
                return response()->json(['error' => 'Equipment not found'], 404);
            }
        }
        


}