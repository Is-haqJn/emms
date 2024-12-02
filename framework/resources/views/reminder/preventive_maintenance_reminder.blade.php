@extends('layouts.admin')
@section('body-title')
@lang('equicare.reminder')
@endsection
@section('title')
| @lang('equicare.preventive_maintenance_reminder')
@endsection
@section('breadcrumb')
<li class="active">@lang('equicare.preventive_maintenance_reminder')</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">@lang('equicare.preventive_maintenance_reminder')
				</h4>
			</div>
			<div class="box-body">
				<div class="table-responsive overflow_x_unset">
					<table class="table table-hover table-bordered table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th> @lang('equicare.equipment_id') </th>
								<th> @lang('equicare.due_date_r') </th>
								<th> @lang('equicare.working_status') </th>
								<th> @lang('equicare.call_registration_date_time')</th>
								<th> @lang('equicare.added_by') </th>
							</tr>
						</thead>
						<tbody>
							@php $count=0; @endphp
							@if (isset($preventive_reminder) && $preventive_reminder->count() > 0)
							@foreach ($preventive_reminder as $p_reminder)
							@php $count++;
							if($p_reminder->next_due_date){
							$now = time(); // or your date as well
							$your_date = strtotime($p_reminder->next_due_date);
							$datediff = $your_date - $now ;

							$remaining_days = round($datediff / (60 * 60 * 24))." days";
							}
							@endphp
							<tr>
								<td> {{ $count }} </td>
								<td> {{ $p_reminder->equipment->unique_id?? '-' }} </td>
								<td> {{ $p_reminder->next_due_date?date_change($p_reminder->next_due_date) ." ($remaining_days)":'-' }}</td>
								<td> {{ $p_reminder->working_status?ucfirst($p_reminder->working_status): '-' }}</td>
								<td> {{ $p_reminder->call_register_date_time? date('Y-m-d h:i A', strtotime($p_reminder->call_register_date_time)) : '-' }}</td>
								<td> {{ $p_reminder->user_attended_fn?ucwords($p_reminder->user_attended_fn->name) : '-'}}
								</td>
							</tr>
								@endforeach
								@else
									<tr class="text-center">
										<td colspan="7">@lang('equicare.no_data_around')</td>
									</tr>
								@endif
							</tbody>
							<tfoot>
								<tr>
									<th>#</th>
									<th> @lang('equicare.equipment_id') </th>
									<th> @lang('equicare.due_date_r') </th>
									<th> @lang('equicare.working_status') </th>
									<th> @lang('equicare.call_registration_date_time')</th>
									<th> @lang('equicare.added_by') </th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection