@extends('layouts.admin')
@section('body-title')
@lang('equicare.sticker')
@endsection
@section('title')
	|  @lang('equicare.s_calibrations')
@endsection
@section('breadcrumb')
<li class="active">@lang('equicare.sticker')</li>
<li class="active">@lang('equicare.calibrations_sticker')</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">	
			<div class="box box-primary">
				<div class="box-header with-border">
					<h4 class="box-title">@lang('equicare.filters')</h4>
				</div>
				<div class="box-body">
					<form action="{{route('admin/calibrations_sticker2')}}" style='with:100%;' name ='filter_form' class='filter_form'>
                    {{-- @csrf --}}
					{{-- {!! Form::open(['action'=>route('admin/calibrations_sticker2'),'method'=>'GET','style'=>'with:100%;','name'=>'filter_form','class'=>'filter_form']) !!} --}}

					<div class="row">
						<div class="form-group col-md-3">
							{!! Form::label('hospital',__('equicare.hospital')) !!}
							{!! Form::select('hospital',$hospitals??[],$hospital??null,['class'=>'form-control select2_hospital','placeholder'=>__('equicare.select')]) !!}
						</div>
						<div class="form-group col-md-3">
							{!! Form::label('unique_id',__('equicare.equipment_id')) !!}
							{!! Form::select('unique_id',$equipments??[],$unique_id??null,['class'=>'form-control select2_equipment','placeholder'=>'Select']) !!}
						</div>
						{!! Form::hidden('excel',null,['class'=>'export_hidden']) !!}
						{!! Form::hidden('pdf',null,['class'=>'pdf_hidden']) !!}
						<div class="form-group col-md-1">
							{!! Form::submit(__('equicare.submit'),['class' => 'btn btn-primary btn-flat','style'=>'margin-top:25px;',
								'name' => 'action',
							]) !!}
							<input type="submit" value="excel" id="excel_hidden" name="excel_hidden" class="hidden"/>
								<input type="submit" value="pdf" id="pdf_hidden" name="pdf_hidden" class="hidden"/>
						</div>
						<div class="form-group col-md-1 col-lg-1 ">
							<a class="btn btn-primary" href="{{route('admin.calibration.index')}}" style="margin-top:25px;margin-right:10px">Reset</a>
						</div>

						<div class="form-group col-md-2">
							{!! Form::submit(__('equicare.generate_stickers'),['class' => 'btn btn-primary btn-flat','style'=>'margin-top:25px;',
								'name' => 'action',
							]) !!}
						</div>
					</div>
					{{-- {!! Form::close() !!} --}}
					</form>
				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header">
					<h4 class="box-title">@lang('equicare.calibration_sticker')</h4>
				</div>
				<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead class="thead-inverse">
							<tr>
								<th> # </th>
								<th> @lang('equicare.hospital') </th>
								<th> @lang('equicare.unique_id') </th>
								<th> @lang('equicare.equipment') </th>
								<th> @lang('equicare.pm_date')</th>
								<th> @lang('equicare.pm_due_date')</th>
								<th> @lang('equicare.calibration_date')</th>
								<th> @lang('equicare.calibration_due_date') </th>
								<th> @lang('equicare.action') </th>
							</tr>
						</thead>
						<tbody>
							{{-- @dd($calibrations->count()) --}}
							@if (isset($calibrations) && count($calibrations) > 0)
							@php($count = 0)
							@foreach ($calibrations as $calibration)
							{{-- @dd($calibration->equipment) --}}
							@php($count++)
							<tr>
								<td> {{ $count }} </td>
								<td>{{ $calibration->equipment?$calibration->equipment->hospital->name : '-' }}
								</td>
								<td>{{ $calibration->equipment->unique_id }}</td>
								<td>{{ $calibration->equipment->name }}</td>

								<td>{{($calibration->equipment->pm != null) ? date_change(date('Y-m-d',strtotime($calibration->equipment->pm->call_register_date_time))): '-' }}
								</td>	
								
								<td>{{($calibration->equipment->pm != null ) ? date_change($calibration->equipment->pm->next_due_date) : '-' }}
								</td>
								
								<td> {{ date_change($calibration->date_of_calibration)??'-' }}
								</td>
								
								<td> {{ date_change($calibration->due_date) ??'-' }}</td>
								<td>
									{{-- @if($calibration->equipment->pm) --}}
									<a href="{{ url('admin/calibrations_sticker',$calibration->id) }}" class="btn btn-flat btn-success btn-sm">@lang('equicare.generate_single_sticker')</a>
								</td>
								{{-- @else --}}
								{{-- <a href="#" class="h4">-</a> --}}
								{{-- @endif --}}
							</tr>
						@endforeach
						@else
							<tr class="text-center">
								<td colspan="8">@lang('equicare.no_data_available')</td>
							</tr>
						@endif
						</tbody>
						<tfoot>
							<tr>
								<th> # </th>
								<th> @lang('equicare.hospital') </th>
								<th> @lang('equicare.unique_id') </th>
								<th> @lang('equicare.equipment') </th>
								<th> @lang('equicare.pm_date')</th>
								<th> @lang('equicare.pm_due_date')</th>
								<th> @lang('equicare.calibration_date')</th>
								<th> @lang('equicare.calibration_due_date') </th>
								<th> @lang('equicare.action') </th>
							</tr>
						</tfoot>
					</table>
				</div>
					@if(isset($calibrations))
					<div class="row">
						<div class="col-md-12">
							{!! $calibrations->render() !!}
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
			$('.select2_equipment, .select2_hospital').select2({
				allowClear:true,
				placeholder:"{{__("equicare.select_option")}}"
			});
			$('select[name=hospital]').change(function(){
				var hospital_id = $(this).val();
				if(hospital_id){
				$.ajax({
					url:"{{ url('admin/calibrations_sticker/get_equipment') }}",
					type:"GET",
					data:{
						'hospital_id':hospital_id
					},
					success:function(data){
						$('.select2_equipment').empty();
						if(data.equipments.length == 0){
							alert('{{__("equicare.select_other_hospital")}}');
						}
						$('.select2_equipment').append('<option val=""></option>');
						$('.select2_equipment').select2({
							data:data.equipments,
							placeholder: "@lang('equicare.equipment_id')"
						});
						// for(key in data.equipments){
						// 	console.log(key,data.equipments[key]);
						// 	$('.select2_equipment').append('<option val="'+key+'">'+data.equipments[key]+'</option>');
						// }
					}
				});
				}
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
