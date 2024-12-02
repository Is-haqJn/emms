<!DOCTYPE html>
<html>
<head>
	{{-- @dd('test') --}}
	<title>@lang('equicare.breakdown_excel')</title>
</head>
<body>
		@if(isset($b_maintenance) && $b_maintenance->count())
		<div class="table-responsive">
		<table id="data_table" class="table table-hover table-bordered table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th> @lang('equicare.equipment_name') </th>
								<th> @lang('equicare.user') </th>
								<th> @lang('equicare.call_handle') </th>
								<th> @lang('equicare.working_status') </th>
								<th> @lang('equicare.report_number') </th>
								<th> @lang('equicare.call_registration_date_time')</th>
								<th> @lang('equicare.attended_by') </th>
								<th> @lang('equicare.first_attended_on') </th>
								<th> @lang('equicare.completed_on') </th>
							</tr>
						</thead>
						<tbody>
							@php $count=0; @endphp
							@if (isset($b_maintenance))
							@foreach ($b_maintenance as $breakdown)
							@php $count++; @endphp
							<tr>
								<td> {{ $count }} </td>
								<td> {{ $breakdown->equipment->name?? '-' }} </td>
								<td> {{ $breakdown->user->name ?? '-'}}</td>
								<td> {{ $breakdown->call_handle?ucfirst($breakdown->call_handle): '-' }} </td>
								<td> {{ $breakdown->working_status?ucfirst($breakdown->working_status): '-' }}</td>
								<td> {{ $breakdown->report_no?sprintf("%04d",$breakdown->report_no):'-'  }} </td>
								<td>
									{{ $breakdown->call_register_date_time? date('Y-m-d h:i A', strtotime($breakdown->call_register_date_time)) : '-' }}
								</td>
								<td>{{$breakdown->user_attended_fn?$breakdown->user_attended_fn->name:'-'}}</td>
								<td>
									{{$breakdown->user_attended_fn?date('Y-m-d H:i A',strtotime($breakdown->call_attend_date_time)):'-'}}
								</td>
								<td>
									{{$breakdown->call_complete_date_time?date('Y-m-d H:i A',strtotime($breakdown->call_complete_date_time)):'-'}}
								</td>
							</tr>
							@endforeach
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th>#</th>
								<th> @lang('equicare.equipment_name') </th>
								<th> @lang('equicare.user') </th>
								<th> @lang('equicare.call_handle') </th>
								<th> @lang('equicare.working_status') </th>
								<th> @lang('equicare.report_number') </th>
								<th> @lang('equicare.call_registration_date_time')</th>
								<th> @lang('equicare.attended_by') </th>
								<th> @lang('equicare.first_attended_on') </th>
								<th> @lang('equicare.completed_on') </th>
							</tr>
						</tfoot>
					</table>
		</div>
		@endif

</body>
</html>