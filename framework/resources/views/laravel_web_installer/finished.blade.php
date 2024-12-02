@extends('layouts.master')

@section('title', trans('installer_messages.final.title'))
@section('container')
    <p class="paragraph aligntext">
        @lang('equicare.thank_you')
	</p>
    <div class="buttons">
        <a href="{{ url('login/') }}" class="button">{{ trans('installer_messages.final.exit') }}</a>
    </div>
@stop
