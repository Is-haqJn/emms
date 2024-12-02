@extends('layouts.admin')
@section('body-title')
@lang('equicare.calibrations')
@endsection
@section('title')
| @lang('equicare.calibrations')
@endsection
@section('breadcrumb')
<li class="active">@lang('equicare.calibrations')</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">@lang('equicare.calibrations')
                    @if(Auth::user()->hasDirectPermission('Create Calibrations'))
					<a href="{{ route('calibration.create') }}" class="btn btn-primary btn-flat">@lang('equicare.add_new')</a>
					@endif
				</h4>
			</div>
			<div class="box-body">
				<div class="table-responsive overflow_x_unset">
					<table id="data_table" class="table table-hover table-bordered table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th> @lang('equicare.calibrations') </th>
								<th> @lang('equicare.user') </th>
								<th> @lang('equicare.calibration_date') </th>
								<th> @lang('equicare.due_date') </th>
								<th> @lang('equicare.certificate-no') </th>
								<th> @lang('equicare.company') </th>
								<th> @lang('equicare.contact_person') </th>
								@if(Auth::user()->hasDirectPermission('Edit Calibrations') || Auth::user()->hasDirectPermission('Delete Calibrations'))
								<th> @lang('equicare.action') </th>
								@endif
							</tr>
						</thead>
						<tbody>
							@php $count=0; @endphp
							@if (isset($calibrations))
							@foreach ($calibrations as $calibration)
							@php $count++; @endphp
							<tr>
								<td>{{ $count }} </td>
								<td>{{ $calibration->equipment->name?? '' }} </td>
								<td>{{ucwords($calibration->user->name)?? '' }} </td>
								<td>{{ date_change($calibration->date_of_calibration) }} </td>
								<td>{{ date_change($calibration->due_date) }} </td>
								<td>{{ $calibration->certificate_no }} </td>
								<td>{{ $calibration->company }} </td>
								<td>{{ $calibration->contact_person }} </td>
								@if(Auth::user()->can('Edit Calibrations') || Auth::user()->can('Delete Calibrations'))
								<td>
									{!! Form::open(['url' =>
									'admin/calibration/'.$calibration->id,'method'=>'DELETE','class'=>'form-inline']) !!}
									@if(Auth::user()->hasDirectPermission('Edit Calibrations'))
									<a href="{{ route('calibration.edit',$calibration->id) }}" class="btn bg-purple btn-sm btn-flat" title="@lang('equicare.edit')"><i class="fa fa-edit"></i> </a>
									@endif &nbsp;
									
									@if(isset($calibration->calibration_certificate))
									<a href="{{asset($calibration->calibration_certificate)}}" target="_blank" download class="btn btn-success btn-sm btn-flat" title="@lang('equicare.calibration_certificate')"><span class="fa fa-download" aria-hidden="true"></span></a>  &nbsp;
									@endif

									<input type="hidden" name="id" value="{{ $calibration->id }}">
									@if(Auth::user()->hasDirectPermission('Delete Calibrations'))
									<button class="btn btn-warning btn-sm btn-flat" type="submit"
										onclick="return confirm('@lang('equicare.are_you_sure')')" title="@lang('equicare.delete')"><span
											class="fa fa-trash-o" aria-hidden="true"></span></button>
									@endif
									{!! Form::close() !!}

								</td>
								@endif
							</tr>
							@endforeach
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th>#</th>
								<th> @lang('equicare.calibrations') </th>
								<th> @lang('equicare.user') </th>
								<th> @lang('equicare.calibration_date') </th>
								<th> @lang('equicare.due_date') </th>
								<th> @lang('equicare.certificate-no') </th>
								<th> @lang('equicare.company') </th>
								<th> @lang('equicare.contact_person') </th>
								@if(Auth::user()->hasDirectPermission('Edit Calibrations') || Auth::user()->hasDirectPermission('Delete Calibrations'))
								<th> @lang('equicare.action') </th>
								@endif
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection