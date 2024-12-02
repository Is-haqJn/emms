@extends('layouts.admin')
@section('body-title')
	@lang('equicare.hospitals')
@endsection
@section('title')
	| @lang('equicare.hospitals')
@endsection
@section('breadcrumb')
<li><a href="{{ url('admin/hospitals') }}">@lang('equicare.hospitals')</a></li>
<li class="active">@lang('equicare.edit')</li>
@endsection
@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">@lang('equicare.edit_hospital')</h4>
				</div>
				<div class="box-body ">
					@include ('errors.list')
					<form class="form" method="post" action="{{ route('hospitals.update',$hospital->id) }}">
						{{ csrf_field() }}
						<input type="hidden" name="_method" value="PATCH"/>
						<div class="row">
						<div class="form-group col-md-6">
							<label for="name"> @lang('equicare.name') </label>
							<input type="text" name="name" class="form-control"
							value="{{ $hospital->name }}" />
						</div>
						<input type="hidden" name="id" class="form-control"
							value="{{ $hospital->id }}" />
						<div class="form-group col-md-6">
							<label for="slug"> @lang('equicare.Short Name') </label>
							<input type="text" name="slug" class="form-control"
							value="{{ $hospital->slug }}" />
						</div>
						<div class="form-group col-md-6">
							<label for="email"> @lang('equicare.email') </label>
							<input type="email" name="email" class="form-control" value="{{ $hospital->email }}"/>
						</div>
						<div class="form-group col-md-6">
							<label for="contact_person"> @lang('equicare.contact_person') </label>
							<input type="text" name="contact_person" class="form-control"
							value="{{ $hospital->contact_person }}" />
						</div>
						<div class="form-group col-md-6">
							<label for="phone_no"> @lang('equicare.phone') </label>
							<input type="text" name="phone_no" class="form-control phone"
							value="{{ $hospital->phone_no }}" />
						</div>
						<div class="form-group col-md-6">
							<label for="mobile_no"> @lang('equicare.mobile') </label>
							<input type="text" name="mobile_no" class="form-control phone"
							value="{{ $hospital->mobile_no }}" />
						</div>
						<div class="form-group col-md-6">
							<label for="address"> @lang('equicare.address') </label>
							<textarea rows="3" name="address" class="form-control"
							>{{ $hospital->address }}</textarea>
						</div>
						<br/>
						<div class="form-group col-md-12">
							<input type="submit" value="@lang('equicare.submit')" class="btn btn-primary btn-flat"/>
						</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection