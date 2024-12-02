@extends('layouts.admin')
@section('body-title')
	@lang('equicare.qr')
@endsection
@section('title')
	| @lang('equicare.qr')
@endsection
@section('breadcrumb')
<li><a href="{{ route('qr.index') }}">@lang('equicare.qr')</a></li>
	<li class="active">@lang('equicare.create_qr')</li>
@endsection
@section('styles')
	<style type="text/css">
		.mt-2{
			margin-top: 10px;
		}
	</style>
@endsection
@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
					<div class="box-header with-border">
						<h4 class="box-title">
							@lang('equicare.create_qr')
						</h4>
					</div>
					<div class="box-body">
					@include ('errors.list')
						{!! Form::open(['url'=>'admin/qr-generate','method'=>'POST']) !!}
						<div class="row">
						
							<div class="form-group col-md-6">
								{!! Form::label('qr_count',__('equicare.qr_count')) !!}
								{!! Form::number('count',old('count'),['class' => 'form-control','required']) !!}
							</div>
							
							<div class="form-group col-md-12">
								{!! Form::submit(__('equicare.submit'),['class' => 'btn btn-primary btn-flat']) !!}
							</div>
						</div>
						{!! Form::close() !!}
					</div>
			</div>
		</div>
	</div>
@endsection