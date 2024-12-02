@extends('layouts.master')

@section('title', 'database migration')
@section('container')

    <div class="buttons">
        <a href="{{ url('migrate') }}" class="button">@lang('equicare.migrate_seed')</a>
    </div>
@stop
