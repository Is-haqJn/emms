@extends('layouts.admin')
@section('body-title')
@lang('equicare.reminder')
@endsection
@section('title')
| @lang('equicare.calibrations_reminder')
@endsection
@section('breadcrumb')
<li class="active">@lang('equicare.calibrations_reminder')</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">@lang('equicare.calibrations_reminder')</h4>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-hover table-bordered table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th> @lang('equicare.equipment_id') </th>
								<th> @lang('equicare.calibration_date') </th>
								<th> @lang('equicare.due_date_r')</th>
								<th> @lang('equicare.added_by') </th>
								<th> @lang('equicare.contact_person') </th>
							</tr>
						</thead>
						<tbody>
							@php $count=0; @endphp
							@if (isset($calibrations) && $calibrations->count())
							@foreach ($calibrations as $calibration)
							@php $count++;
							if($calibration->due_date){
								$now = time(); // or your date as well
								$your_date = strtotime($calibration->due_date);
								$datediff = $your_date - $now ;

								$remaining_days = round($datediff / (60 * 60 * 24))." days";
							}
							@endphp
							<tr>
								<td>{{ $count }} </td>
								<td>{{ ucwords($calibration->equipment->unique_id??'-')?? '' }} </td>
								<td>{{ date_change($calibration->date_of_calibration) }} </td>
								<td>{{ $calibration->due_date?date_change($calibration->due_date) . " ($remaining_days)":'-' }} </td>
								<td>{{ucwords($calibration->user->name)?? '' }} </td>
								<td>{{ $calibration->contact_person }} </td>
							</tr>
							@endforeach
							@else
							<tr class="text-center">
								<td colspan="6">@lang('equicare.no_data_available')</td>
							</tr>
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th>#</th>
								<th> @lang('equicare.equipment_id') </th>
								<th> @lang('equicare.calibration_date') </th>
								<th> @lang('equicare.due_date_r')</th>
								<th> @lang('equicare.added_by') </th>
								<th> @lang('equicare.contact_person') </th>
							</tr>
						</tfoot>
					</table>
				</div>
				@if (isset($calibrations) && $calibrations->count())
				<div class="row text-center">
					<div class="col-md-12">{{ $calibrations->render() }}</div>
				</div>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection