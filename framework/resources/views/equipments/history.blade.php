@extends('layouts.app')
@section('body-title')
@lang('equicare.equipment_history')
@endsection
@section('title')
| @lang('equicare.equipment_history')
@endsection
@section('breadcrumb')
<li class="active">@lang('equicare.equipment_history')</li>
@endsection

@section('content')
<style>
  .sticky-box {
    position: fixed;
    bottom: 0;
    left:0;
    display:flex;
    justify-content:center;
    align-items:center;
    /* inset:0; */
    width:100%;
    background: #fff;
    border-top: 2px solid #3C8DBC;
    border-radius: 4px;
}
@media (max-width: 768px) {
    .sticky-box {
        position: fixed;
    bottom: 0;
    /* width: 85%; */
    background: #fff;
    border-top: 2px solid #3C8DBC;
    border-radius: 4px;
    }
}
    </style>
<div class="container">
@if(session('success'))
        <div class="alert alert-success" id="alert-message">
            {{ session('success') }}
        </div>
    @elseif(session('flash_message'))
        <div class="alert alert-warning" id="alert-message">
            {{ session('flash_message') }}
        </div>
    @endif

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <h2>@lang('equicare.equipment_history')</h2>
            <div class="box box-primary">
                <div class="box-header with-border"
                    style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="flex: 0; text-align: center;">
                        @if($equip_img && $equip_img->thumbnail_image)  
                        <img src="{{ asset('/uploads/EquipImages/' . $equip_img->thumbnail_image) }}"
                            alt="Thumbnail Image" style="height: 80px; width: 80px; object-fit: cover;">
                        @endif
                    </div>
                    <div style="flex: 2; text-align: center;">
                        <h4 class="box-title">
                            <b>{{$equipment->name ?? ''}}</b>
                        </h4>
                    </div>
                    <div style="flex: 0; text-align: center;">
                        @if(\Auth::user())
                        <a href="{{ route('equipments.edit', $equipment->id) }}" class="h4"
                            title="@lang('equicare.edit')">
                            <i class="fa fa-edit purple-color"></i> @lang('equicare.edit')
                        </a>
                        @endif
                    </div>
                </div>


                <div class="box-body">
                    <div class="row">
                        @include('equipments.equipment')
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- The time line -->
                <ul class="timeline">

                    @if($data->count() > 0)
                    @foreach($data as $d)
                    <!-- timeline time label -->
                    <li class="time-label">
                        <span class="bg-red">
                            {{date('Y-m-d',strtotime($d['created_at']))}}
                        </span>
                    </li>
                  
                    <li>
                        @if($d['type'] == 'Call')
                        <i class="fa fa-phone bg-green"></i>
                        @else
                        <i class="fa fa-balance-scale bg-green"></i>
                        @endif

                        <div class="timeline-item">
                            <span class="time">
                                <i class="fa fa-clock-o"></i> {{date('h:i A',strtotime($d['created_at']))}}
                            </span>
                            <span class="time">
                                @if($d['type'] == 'Call' && $d['call_type'] == 'breakdown' && \Auth::user())
                                <a href="{{ route('breakdown_maintenance.edit',$d['id']) }}"
                                    title="@lang('equicare.edit')" class="h4"><i class="fa fa-edit purple-color"></i>
                                    @lang('equicare.edit') </a>
                                @elseif($d['type'] == 'Call' && $d['call_type'] == 'preventive' && \Auth::user())
                                <a href="{{ route('preventive_maintenance.edit',$d['id']) }}"
                                    title="@lang('equicare.edit')" class="h4"><i class="fa fa-edit purple-color"></i>
                                    @lang('equicare.edit') </a>
                                @else
                                @if(\Auth::user())
                                <a href="{{ route('calibration.edit',$d['id']) }}" title="@lang('equicare.edit')"
                                    class="h4"><i class="fa fa-edit purple-color"></i> @lang('equicare.edit') </a>
                                @endif
                                @endif
                            </span>
                            <h3 class="timeline-header text-blue">
                                <b>{{$d['type']}}
                                    @if($d['type'] == 'Call')
                                    - {{$d['call_type']}}
                                    @endif
                                </b>
                            </h3>

                            <div class="timeline-body">
                                <div class="row">
                                    @if($d['type'] == 'Call')
                                    @include('equipments.call')
                                    @else
                                    @include('equipments.calibration')
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                    @else
                    <!-- timeline item -->
                    <li>
                        <i class="fa fa-circle bg-green"></i>

                        <div class="timeline-item">
                            <h3 class="timeline-header text-blue">
                                No History Found for this Equipment.
                            </h3>

                            <div class="timeline-body">

                            </div>
                        </div>
                    </li>
                    @endif
                    <li>

                        <i class="fa fa-clock-o bg-gray"></i>
                    </li>
                </ul>
            </div>
        </div>
        
    </div>
</div>
@php
    $breakdown = \App\CallEntry::where('equip_id', $equipment->id)->get();
    $user = Auth::user();
    $settings = App\Setting::first();
@endphp

@if ($user || ($settings && $settings->allow_guest == 1))
    {{-- Only display this section if the user is logged in or guest access is allowed --}}
    @if ($breakdown->isEmpty() || $breakdown)
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="position: relative; height:600px;">
                    <div class="sticky-box">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-body">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createBreakdownModal">
                                        Create Breakdown
                                    </button>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif



<div class="modal fade" id="createBreakdownModal" tabindex="-1" role="dialog" aria-labelledby="createBreakdownModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                <h5 class="modal-title" id="createBreakdownModalLabel">Create Breakdown</h5>
            </div>
            <form id="breakdownForm" method="POST" action="{{ route('breakdown.store') }}">
                @csrf
                <div class="modal-body">
                @php ($user = Auth::user())
                    @if($user)
                    <div class="form-group">
							<label>@lang('equicare.call_handle'):</label>
							<div class="radio iradio">
								<label class="login-padding">
									{!! Form::radio('call_handle', 'internal')!!} @lang('equicare.internal')
								</label>
								<label>
									{!! Form::radio('call_handle', 'external',null,['id'=>'external'])!!} @lang('equicare.external')
								</label>
							</div>
						</div>
						<div class="form-group report_no none-display">
							<label for="department"> @lang('equicare.report_number')  </label>
							<input type="text" name="report_no" class="form-control" value="" />
						</div>

                        <div class="form-group">
							<label for="department"> @lang('equicare.call_registration_date_time') </label>
							<div class="input-group">
								<input type="text" name="call_register_date_time" class="form-control call_register_date_time"
									value="" />
								<span class="input-group-addon">
									<i class="fa fa-clock-o"></i>
								</span>
							</div>
						</div>

                        <div class="form-group">
							<label>@lang('equicare.working_status')</label>
							{!! Form::select('working_status',[
							'working' => __("equicare.working"),
							'not working' => __("equicare.not_working"),
							'pending' => __("equicare.pending")
							],null,['placeholder' => '--select--','class' => 'form-control']) !!}
						</div>

                        <div class="form-group">
                            <label for="nature_of_problem">Nature of Problem</label>
                            <textarea class="form-control" name="nature_of_problem" rows="2" required></textarea>
                        </div>
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                    @else
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="nature_of_problem">Nature of Problem</label>
                            <textarea class="form-control" name="nature_of_problem" rows="2" required></textarea>
                        </div>
                    @endif
                    <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datetimepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/datetimepicker.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // console.log("page is load");    
	if($('#external').attr('checked') =='checked'){
        // console.log("checked");
			$('.report_no').css('display','block');
		}
		$('#external').on('ifChecked ifUnchecked',function(e){
			if(e.type == 'ifChecked'){
				$('.report_no').show();
			}else{
				$('.report_no').hide(); 
			}
		});
        
});
$('.call_register_date_time').datetimepicker({
		sideBySide: true,
	});
    document.addEventListener('DOMContentLoaded', function () {
        const alertMessage = document.getElementById('alert-message');
        if (alertMessage) {
            setTimeout(function () {
                alertMessage.style.transition = 'opacity 1s ease-out';
                alertMessage.style.opacity = '0';
                setTimeout(function () {
                    alertMessage.remove();
                }, 1000); 
            }, 3000); 
        }
    });
</script>
@endsection





