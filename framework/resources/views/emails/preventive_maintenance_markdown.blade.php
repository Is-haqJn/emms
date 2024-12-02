@component('mail::layout')
    {{-- Header --}}
    @php($settings = \App\Setting::first())
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            @if ($settings != null)
                @if ($settings->logo != null)
                    <img src="{{ asset('uploads/' . $settings->logo) }}" class="logo-style">
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
    # @lang('equicare.preventive_maintenance_email_reminder')


    @lang('equicare.hello'), {{ $user->name }}




    @component('mail::panel')
        <table class="fontsize" width="100%;">
            <tr>
                <th>@lang('equicare.equipment_id')</th>
                <th>@lang('equicare.due_date_r')</th>
                <th>@lang('equicare.working_status')</th>

                <th>@lang('equicare.call_register_date')</th>
                <th>@lang('equicare.added_by')</th>
            </tr>
            <tr>
                <th></th>
            </tr>
            @foreach ($preventive as $pm)
                <?php
                $now = time();
                $due_date = strtotime($pm->next_due_date);
                $date_diff = $due_date - $now;
                $days_remaining = ceil($date_diff / (60 * 60 * 24));
                ?>


                <tr class="aligntext">
                    <td>{{ $pm->equipment->unique_id ?? '-' }}</td>
                    <td>{{ $pm->next_due_date . ' (' . $days_remaining . ' days)' }}</td>
                    <td>{{ $pm->working_status }}</td>
                    <td>{{ $pm->call_register_date_time ? date('Y-m-d', strtotime($pm->call_register_date_time)) : '-' }}</td>
                    <td>{{ $pm->user->name }}</td>
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
