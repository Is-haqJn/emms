
<!DOCTYPE html>
<html>
<head>
	<title>@lang('equicare.preventive_pdf')</title>
	<style type="text/css">
	html{
width: 100%;
height: 100%;
padding: 0;
margin: 10px;
}
	.table ,td,th{
		border:1px solid;
		text-align: center;
		font-size: 14px;
	}
	td,th{
		padding: 2px 5px 2px 5px;
	}
	.table{
		border-collapse: collapse;
		overflow: scroll;
	}

	.table-responsive{
		width: 90%;
	}
	.page-break{
		page-break-after: always;
	}
	.container-fluid{
		width:100%;
	}


</style>
</head>
<body>
	<div class="container-fluid">
		@if(isset($preventives) && $preventives->count())
		<h2>@lang('equicare.preventives') </h2>
		@php($c= 0)
		@if(isset($preventives) && $preventives->count())
		@php($preventives_chunk = $preventives->chunk(10))
		@foreach ($preventives_chunk as $k => $chunk)
		@php($c = $c + 10)
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
                                    <th> @lang('equicare.next_due_date')</th>
                                    <th> @lang('equicare.attended_by') </th>
                                    <th> @lang('equicare.first_attended_on') </th>
                                    <th> @lang('equicare.completed_on') </th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @php($count=0)
                                @if (isset($preventives))
                                    @foreach ($preventives as $preventive)
                                        @php($count++)
                                        <tr>
                                            <td> {{ $count }} </td>
                                            <td> {{ $preventive->equipment->name ?? '-' }} </td>
                                            <td> {{ $preventive->user->name ?? '-' }}</td>
                                            <td> {{ $preventive->call_handle ? ucfirst($preventive->call_handle) : '-' }} </td>
                                            <td> {{ $preventive->working_status ? ucfirst($preventive->working_status) : '-' }}
                                            </td>
                                            <td> {{ $preventive->report_no ? sprintf('%04d', $preventive->report_no) : '-' }}
                                            </td>
                                            <td>
                                                {{ $preventive->call_register_date_time ? date('Y-m-d h:i A', strtotime($preventive->call_register_date_time)) : '-' }}
                                            </td>
                                            <td> {{ date_change($preventive->next_due_date) ?? '-' }}</td>
                                            <td>{{ $preventive->user_attended_fn ? $preventive->user_attended_fn->name : '-' }}
                                            </td>
                                            <td>{{ $preventive->user_attended_fn ? date('Y-m-d H:i A', strtotime($preventive->call_attend_date_time)) : '-' }}
                                            </td>
                                            <td>{{ $preventive->call_complete_date_time ? date('Y-m-d H:i A', strtotime($preventive->call_complete_date_time)) : '-' }}
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
                                    <th> @lang('equicare.next_due_date')</th>
                                    <th> @lang('equicare.call_registration_date_time')</th>
                                    <th> @lang('equicare.attended_by') </th>
                                    <th> @lang('equicare.first_attended_on') </th>
                                    <th> @lang('equicare.completed_on') </th>
                                   
                                </tr>
                            </tfoot>
                        </table>
		</div>
		@if($c % 10 == 0 && !($loop->last == $preventives_chunk[$k]))
			<div class="page-break"></div>
		@endif
		@php($c = 0)
		@endforeach
		@endif
        @else
					<p class="text-center" style="text-align: center;">@lang('equicare.no_data_found')</p>
		@endif	
	</div>
</body>
</html>