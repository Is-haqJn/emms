<div class="col-md-3 mt-4">
   <b>@lang('equicare.equip_id')</b> : {{$equipment->id}}
</div>

<div class="col-md-3">
   <b>@lang('equicare.unique_id')</b> : {{$equipment->unique_id}}
</div>

<div class="col-md-3">
   <b>@lang('equicare.short_name')</b> : {{ $equipment->short_name }}
</div>
<div class="col-md-3">
   <b>@lang('equicare.user')</b> : {{ $equipment->user?ucfirst($equipment->user->name):'-' }}
</div>

<div class="col-md-3">
   <b>@lang('equicare.company')</b> : {{ $equipment->company?? '-' }}
</div>

<div class="col-md-3">
   <b>@lang('equicare.model')</b> : {{ $equipment->model ?? '-' }}
</div>

<div class="col-md-3">
   <b>@lang('equicare.hospital')</b> : {{ $equipment->hospital?$equipment->hospital->name:'-' }}
</div>

<div class="col-md-3">
   <b>@lang('equicare.serial_no')</b> : {{ $equipment->sr_no }}
</div>

<div class="col-md-3">
   <b>@lang('equicare.department')</b> : {{ ($equipment->get_department->short_name)??"-" }} ({{ ($equipment->get_department->name) ??'-' }})
</div>
<div class="col-md-3">
   <b>@lang('equicare.purchase_date')</b> : {{ $equipment->date_of_purchase?? '-' }}
</div>

<div class="col-md-3">
   <b>@lang('equicare.order_date')</b> : {{ $equipment->order_date?? '-' }}
</div>

<div class="col-md-3">
   <b>@lang('equicare.installation_date')</b> : {{ $equipment->date_of_installation??'-' }}
</div>

<div class="col-md-3">
   <b>@lang('equicare.warranty_date')</b> : {{ $equipment->warranty_due_date??'-' }}
</div>