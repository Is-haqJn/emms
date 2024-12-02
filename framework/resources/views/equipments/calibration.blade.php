<div class="col-md-4">
   <b>@lang('equicare.user') : </b> {{ucwords($d['user']['name']) ?? '' }}
</div>

<div class="col-md-4">
   <b>@lang('equicare.calibration_date') : </b> {{$d['date_of_calibration']}}
</div>

<div class="col-md-4">
   <b>@lang('equicare.due_date') : </b> {{$d['due_date']}}
</div>

<div class="col-md-4">
   <b>@lang('equicare.certificate-no') : </b> {{$d['certificate_no']}}
</div>

<div class="col-md-4">
   <b>@lang('equicare.company') : </b> {{$d['company']}}
</div>

<div class="col-md-4">
   <b>@lang('equicare.contact_person') : </b> {{$d['contact_person']}}
</div>