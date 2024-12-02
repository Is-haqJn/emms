@component('mail::layout')
    {{-- Header --}}
     @php($settings = \App\Setting::first())
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
        @if($settings != null)
            @if($settings->logo != null)
                <img src="{{ asset('uploads/'.$settings->logo) }}" class="logo-style">

            @elseif($settings->company != null)
            {{ $settings->company }}
            @else
                {{ config('app.name') }}
            @endif
        @else
            {{ config('app.name') }}
        @endif
        @endcomponent
    @endslot

    {{-- Body --}}
    # @lang('equicare.calibrations_email_reminder')


    @lang('equicare.hello'), {{ $user->name }}

@component('mail::panel')
<table class="fontsize" width="100%;">
	<tr>
		<th>@lang('equicare.equipment_id')</th>
		<th>@lang('equicare.calibration_date')</th>
		<th>@lang('equicare.due_date_e')</th>
		<th>@lang('equicare.added_by')</th>
		<th>@lang('equicare.contact_person')</th>
	</tr>
	<tr>
		<th></th>
	</tr>


@foreach($calibrations as $calibration)
<?php
$now = time();
$due_date = strtotime($calibration->due_date);
$date_diff = $due_date - $now;
$dr = ceil($date_diff / (60 * 60 * 24));
?>

<tr class="aligntext">
	<td>{{ $calibration->equipment->unique_id }}</td>
	<td>{{ $calibration->date_of_calibration }}</td>
	<td>{{ $calibration->due_date . ' ('. $dr .' days)'}}</td>
	<td>{{ $calibration->user->name }}</td>
	<td>{{ $calibration->contact_person }}</td>
</tr>
@endforeach
</table>
@endcomponent



@lang('equicare.thanks'),<br>
{{ config('app.name') }}

     {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. @lang('equicare.all_rights_reserved').
        @endcomponent
    @endslot
@endcomponent