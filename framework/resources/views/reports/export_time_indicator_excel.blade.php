<!DOCTYPE html>
<html>
<head>
	<title>@lang('equicare.time_excel_report')</title>
</head>
<body>
	@php
					function format_interval(DateInterval $interval) {
    $result = "";
    if ($interval->y) { $result .= $interval->format("%y years "); }
    if ($interval->m) { $result .= $interval->format("%m months "); }
    if ($interval->d) { $result .= $interval->format("%d day(s) "); }
    if ($interval->h) { $result .= $interval->format("%h hours "); }
    if ($interval->i) { $result .= $interval->format("%i minutes "); }

    return $result;
}
function convert_seconds($seconds)
 {
  $dt1 = new DateTime("@0");
  $dt2 = new DateTime("@$seconds");
  return format_interval($dt1->diff($dt2));
  }

function calculateIntervalAverage() {
	$arr = func_get_args();
    $offset = new DateTime('@0');

    foreach ($arr as  $int) {
    	$count_i = count($int);
    	foreach ($int as $interval) {

        	$offset->add($interval);
    	}
    }

    return convert_seconds(round($offset->getTimestamp() / $count_i));
}
					@endphp
	<table class="table table-striped table-hover table-bordered">
		<thead>
			<tr>
				<th> # </th>
				<th> @lang('equicare.equip_id') </th>
				<th> @lang('equicare.hospital') </th>
				<th> @lang('equicare.call_type') </th>
				<th> @lang('equicare.attended_by') </th>
				<th> @lang('equicare.response_time') </th>
				<th> @lang('equicare.breakdown_time') </th>
				<th> @lang('equicare.attend_to_complete_time') </th>
			</tr>
		</thead>
		<tbody>
			@if (isset($call_entries) && $call_entries->count() > 0)
			@php $count=0;

			@endphp
			@foreach ($call_entries as $entry)
			@php
			$count++;
			$reg_dt = new DateTime($entry->call_register_date_time);
			$attend_dt = new DateTime($entry->call_attend_date_time);
			$complete_dt = new DateTime($entry->call_complete_date_time);

// Response time
			$res_interval = $reg_dt->diff($attend_dt);
			$resposne_time = format_interval($res_interval);
			if ($entry->call_attend_date_time) {
				$res_avg[] = $res_interval;
			}

// Breakdown time
			$breakdown_interval = $reg_dt->diff($complete_dt);
			$breakdown_time = format_interval($breakdown_interval);
			if ($entry->call_register_date_time && $entry->call_complete_date_time) {
				$breakdown_avg[] = $breakdown_interval;
			}
// Attend to Complete time
			$attend_to_complete_interval = $attend_dt->diff($complete_dt);
			$attend_to_complete_time = format_interval($attend_to_complete_interval);
			if ($entry->call_attend_date_time && $entry->call_complete_date_time) {
				$attend_to_complete_avg[] = $attend_to_complete_interval;
			}
			@endphp

			<tr>
				<td> {{ $count }}</td>
				<td> {{ $entry->equipment->unique_id }}</td>
				<td> {{ $entry->equipment->hospital->name }}</td>
				<td> {{ ucfirst($entry->call_handle) }}</td>
				<td> {{ $entry->user_attended_fn->name ?? '' }}</td>
				<td> {{ $entry->call_attend_date_time?$resposne_time:'-' }} </td>
				<td> {{ $entry->call_complete_date_time?$breakdown_time:'-' }}</td>
				<td> {{ $entry->call_complete_date_time?$attend_to_complete_time:'-' }}</td>
			</tr>

			@endforeach
			@if(isset($res_avg))
			<input type="hidden" name="res_avg" id="res_avg" value="{{ calculateIntervalAverage($res_avg) }}">
			@endif
			@if(isset($breakdown_avg))
			<input type="hidden" name="brk_avg" id="brk_avg" value="{{ calculateIntervalAverage($breakdown_avg) }}">
			@endif
			@if(isset($attend_to_complete_avg))
			<input type="hidden" name="att_to_cmplt_avg" id="att_to_cmplt_avg" value="{{ calculateIntervalAverage($attend_to_complete_avg) }}">
			@endif
			@else
			<tr class="text-center">
				<td colspan="8"> No Data available </td>
			</tr>
			@endif
		</tbody>
		<tfoot>
			<tr>
				<th> # </th>
				<th> @lang('equicare.equip_id') </th>
				<th> @lang('equicare.hospital') </th>
				<th> @lang('equicare.call_type') </th>
				<th> @lang('equicare.attended_by') </th>
				<th> @lang('equicare.response_time') </th>
				<th> @lang('equicare.breakdown_time') </th>
				<th> @lang('equicare.attend_to_complete_time') </th>
			</tr>
		</tfoot>
	</table>
</body>
</html>
