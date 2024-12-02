<div class="col-md-4">
   <b>@lang('equicare.user') : </b> {{ucwords($d['user']['name']) ?? '' }}
</div>

<div class="col-md-4">
   <b>@lang('equicare.call_handle') : </b> {{$d['call_handle']}}
</div>

<div class="col-md-4">
   <b>@lang('equicare.working_status') : </b> {{$d['working_status']}}
</div>

<div class="col-md-4">
   <b>@lang('equicare.report_number') : </b> {{$d['report_no']}}
</div>

<div class="col-md-4">
   <b>@lang('equicare.next_due_date') : </b> {{$d['next_due_date']}}
</div>

<div class="col-md-4">
   <b>@lang('equicare.call_registration_date_time') : </b>{{ isset($d['call_register_date_time']) ? date('Y-m-d h:i A', strtotime($d['call_register_date_time'])) : '-' }}

</div>

<div class="col-md-4">
   <b>@lang('equicare.attended_by') : </b> {{ $d['user_attended_fn']?ucwords($d['user_attended_fn']['name']):'-'}}
</div>

<div class="col-md-4">
   <b>@lang('equicare.first_attended_on') : </b> {{$d['user_attended_fn']?date('Y-m-d h:i A',strtotime($d['call_attend_date_time'])):'-'}}
</div>

<div class="col-md-4">
   <b>@lang('equicare.completed_on') : </b> {{!is_null($d['call_complete_date_time'])?date('Y-m-d h:i A',strtotime($d['call_complete_date_time'])) : '-'}}
</div>