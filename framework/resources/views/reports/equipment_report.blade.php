@extends('layouts.admin')
@section('body-title')
@lang('equicare.report')
@endsection
@section('title')
	| @lang('equicare.report_equipment')
@endsection
@section('breadcrumb')
<li class="active">@lang('equicare.report')</li>
<li class="active">@lang('equicare.equipment_report')</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header">
					<h4 class="box-title">@lang('equicare.filters')</h4>
				</div>
				<div class="box-body">

					{!! Form::open(['method'=>'GET','style'=>'with:100%;','name'=>'filter_form','class'=>'filter_form','route'=>'equipment_report_post']) !!}

					<div class="row">
						<div class="form-group col-md-2">
							{!! Form::label('hospital',__('equicare.hospital')) !!}
							{!! Form::select('hospital',$hospitals??[],$hospital??null,['class'=>'form-control hospital_select','placeholder'=>'Select']) !!}
						</div>
						{!! Form::hidden('excel',null,['class'=>'export_hidden']) !!}
						{!! Form::hidden('pdf',null,['class'=>'pdf_hidden']) !!}
						<div class="form-group col-md-2">
							{!! Form::label('working_status',__('equicare.working_status')) !!}
							{!! Form::select('working_status',[
								'working' => __("equicare.working"),
								'not working' => __("equicare.not_working"),
								'pending' => __("equicare.pending")
								],$working_status??null,['placeholder' => 'Select','class' => 'form-control test working_status']) !!}
						</div>
						<div class="form-group col-md-2">
							{!! Form::submit(__('equicare.submit'),['class' => 'btn btn-primary btn-flat','style'=>'margin-top:25px;',
								'name' => 'action',
							]) !!}
							<input type="submit" value="excel" id="excel_hidden" name="excel_hidden" class="hidden"/>
								<input type="submit" value="pdf" id="pdf_hidden" name="pdf_hidden" class="hidden"/>

						</div>
					</div>

					{!! Form::close() !!}
				</div>
			</div>
			<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">@lang('equicare.equipment_report')</h4>
				{!! Form::label('excel_hidden',__('equicare.export_excel'),['class' => 'btn btn-success btn-flat excel','name'=>'action','tabindex'=>1]) !!}
				{!! Form::label('pdf_hidden',__('equicare.export_pdf'),['class' => 'btn btn-primary btn-flat pdf','name'=>'action','tabindex'=>2]) !!}
				</div>
				<div class="box-body table-responsive">
					<table class="table table-bordered table-hover">
						<thead class="thead-inverse">
							<tr>
								<th> # </th>
								<th> @lang('equicare.hospital') </th>
								<th> @lang('equicare.unique_id') </th>
								<th> @lang('equicare.status') </th>
								<th> @lang('equicare.call_attended_by')</th>
								<th> @lang('equicare.call_register_date_time')</th>
								<th> @lang('equicare.call_complete_date_time')</th>
								<th> @lang('equicare.purchase_date') </th>
							</tr>
						</thead>
						<tbody>
							@if (isset($call_entries) && count($call_entries) > 0)
							@php
								$count = 0;
							@endphp
							@foreach ($call_entries as $call_entry)
							@php
								$count++;
							@endphp
							<tr>
								<td> {{ $count }} </td>
								<td>{{ $call_entry->equipment?$call_entry->equipment->hospital->name : '-' }}
								</td>
								<td>{{ $call_entry->equipment->unique_id??'-' }}</td>
								<td>{{ ucwords($call_entry->working_status??'-') }}
								</td>
								<td>{{ $call_entry->user_attended_fn->name?? '-' }}
								</td>
								<td>{{$call_entry->call_register_date_time?date('Y-m-d h:i A',strtotime($call_entry->call_register_date_time)): '-' }}</td>
								<td>{{$call_entry->call_complete_date_time?date('Y-m-d h:i A',strtotime($call_entry->call_complete_date_time)): '-' }}</td>
								<td>{{ date_change($call_entry->equipment->date_of_purchase)?? '-' }}</td>
							</tr>
						@endforeach
						@else
							<tr class="text-center">
								<td colspan="8"> @lang('equicare.no_data_available')</td>
							</tr>
						@endif
						</tbody>
						<tfoot>
							<tr>
								<th> # </th>
								<th> @lang('equicare.hospital') </th>
								<th> @lang('equicare.unique_id') </th>
								<th> @lang('equicare.status') </th>
								<th> @lang('equicare.call_attended_by')</th>
								<th> @lang('equicare.call_register_date_time')</th>
								<th> @lang('equicare.call_complete_date_time')</th>
								<th> @lang('equicare.purchase_date') </th>amp; time</th>
							</tr>
						</tfoot>
					</table>
					@if(isset($call_entries) && empty($excel))
					<div class="row">
						<div class="col-md-12">
							{!! $call_entries->appends(request()->except('page','_token'))->render() !!}
						</div>
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script src="{{ asset('assets/js/datetimepicker.js') }}" type="text/javascript"></script>
	<script type="text/javascript">
		$(function() {
			$('.excel').on('click',function(){
			 	var clicked = $('.export_hidden').val("1");
			 	$('.filter_form').submit();
			});
			$('.pdf').on('click',function(){
			 	var clicked = $('.pdf_hidden').val("1");
			 	$('.filter_form').submit();
			});
		});
	</script>
@endsection
@section('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}">
	<style type="text/css">
		.table{
			border-collapse: collapse;
		}
	</style>
@endsection
