@extends('layouts.app')

@section('content')
	<div class="row">
	<div class='col-md-8 col-md-offset-2'>
		<h1><center><span  class="font100size">@lang('equicare.401')</span><br>
		<b>@lang('equicare.access')</b> @lang('equicare.denied')</center></h1>
	</div>
	<br/>
	<div class='col-md-8 col-md-offset-2'>
		<center><a href="{{ url()->previous() }}">@lang('equicare.back')</a></center>
	</div>
</div>
@endsection

