@extends('layouts.admin')
@section('body-title')
	@lang('equicare.change_password')
@endsection
@section('title')
	| @lang('equicare.change_password')
@endsection
@section('breadcrumb')
	<li class="active">@lang('equicare.change_password')</li>
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
							@lang('equicare.change_password')
						</h4>
					</div>
					<div class="box-body">
						@if ($errors->any())
                      <div class="alert alert-danger alert-dismissible col-md-12">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <ul class="mb-0">

                          @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                          @endforeach
                        </ul>
                      </div>
                    @endif
                    @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible col-md-12">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <ul class="mb-0">
                          <li>{{ session()->get('success') }}</li>
                        </ul>
                    </div>
					@endif
						{!! Form::open(['route'=>'change-password.store','method'=>'POST']) !!}
						<div class="row mb-1">
					<div class="col-md-4 form-group">
						{{ Form::label(__('equicare.old_password'))}}
						</div>
						<div class="col-md-4">
						<input type="password" name="old_password" class="form-control {{ $errors->has('old_password') ? ' is-invalid' : '' }}" placeholder="@lang('equicare.old_password')">
						 @if ($errors->has('old_password'))
				            <span class="invalid-feedback" role="alert">
				              <strong>{{ $errors->first('old_password') }}</strong>
				            </span>
				          @endif
					</div>
				</div>
				<div class="row mb-1">
					<div class="col-md-4 form-group">
						{{ Form::label(__('equicare.new_password'))}}
						</div>
						<div class="col-md-4">
						<input type="password" name="new_password" class="form-control {{ $errors->has('new_password') ? ' is-invalid' : '' }}" placeholder="@lang('equicare.enter_password')">
						 @if ($errors->has('new_password'))
				            <span class="invalid-feedback" role="alert">
				              <strong>{{ $errors->first('new_password') }}</strong>
				            </span>
				          @endif
					</div>
				</div>
				<div class="row mb-1">
					<div class="col-md-4 form-group">
						{{ Form::label(__('equicare.confirm_password'))}}
						</div>
						<div class="col-md-4">
						<input type="password" name="cpassword" class="form-control {{ $errors->has('cpassword') ? ' is-invalid' : '' }}" placeholder="@lang('equicare.enter_password')">
						 @if ($errors->has('cpassword'))
				            <span class="invalid-feedback" role="alert">
				              <strong>{{ $errors->first('cpassword') }}</strong>
				            </span>
				          @endif
					</div>
				</div>
						<div class="row">

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
