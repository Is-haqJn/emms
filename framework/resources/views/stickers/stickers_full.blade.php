<!DOCTYPE html>
<html>
<head>
	<title>@lang('equicare.sticker_generate')</title>
	<style type="text/css">
		.container{
			width: 700px;
		}
		.card{
			width: 50%;
			border: 1px solid;
			border-radius: 10px;
			padding:5px;
			float: left;
			margin-right: 10px;
			margin-bottom: 10px;
		}
		.card > span{
			line-height: 1.5;
			font-size: 12px;
		}
		.page-break {
		    page-break-after: always;
		}
	</style>
</head>
<body>
	<div class="container">
		@php
		$count = 0;
		$card_count = 0;
		$page = 1;
		$t_page = 12;
		@endphp

		@if($calibrations->count())

	@foreach ($calibrations as $calibration)
			@if($count == 2 )
			@php
			$count = 0;
			@endphp
			<div style="clear: both;"></div>

			@endif
			@php
			$count++;
			@endphp
		    <div class="card">
				@php
				// \App\Equipment::select('*')->delete();
				$u_e_id = (\App\QrGenerate::where('id',$calibration->equipment->qr_id)->first() !=null ? (\App\QrGenerate::where('id',$calibration->equipment->qr_id)->first()->uid) : '')

				@endphp
								<img src="{{ asset('/uploads/qrcodes/qr_assign/'.$u_e_id.'.png') }}" style="float:right; padding:5px; width: 100px;">
			<span><b>Equipment ID </b> : {{ $calibration->equipment->unique_id}}</span><br/>
			<span><b>Equipment Name</b> : {{ $calibration->equipment->name}}</span>
			<br>
			<span><b>Date of PM</b> :
				
				
				{{-- {{ $calibration->equipment->call_entry?date('Y-m-d',strtotime($calibration->equipment->pm->call_register_date_time)): '-'}} --}}
				{{$calibration->equipment->pm ? date_change($calibration->equipment->pm->call_register_date_time): '-'}}

			</span>
			<br/>
			<span><b>Due Date of PM</b> :
				{{-- {{ date_change($calibration->equipment->pm->next_due_date)?? '-'}} --}}
				{{($calibration->equipment->pm != null ) ? date_change($calibration->equipment->pm->next_due_date) : '-' }}
			</span>
			<br/>
			<span><b>Calibration Date</b> : {{ date_change($calibration->date_of_calibration)}}
			</span>
			<br/>
			<span><b>Calibration Due Date</b> : {{ date_change($calibration->due_date)}}
			</span>
			<br/>
			<span><b>Engineer Contact No</b> : {{ $calibration->engineer_no}}
			</span>
		</div>
		 @if($page % 12 == 0)
			<div class="page-break"></div>
		@endif
		@php($page = 0)
		@php($page++)
	@endforeach
	@else
	<div style="text-align: center;"><strong ><span>No Calibrations</span></strong>
	</div>
	@endif
	</div>
</body>
</html>