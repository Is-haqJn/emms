@extends('layouts.admin')
@section('body-title')
@lang('equicare.permissions')
@endsection
@section('title')
	| @lang('equicare.permissions')
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('admin/permissions') }}">@lang('equicare.permissions')</a></li>
<li class="breadcrumb-item active">@lang('equicare.create')</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">@lang('equicare.create_permission')</h4>
				</div>

				<div class="box-body ">
					@include ('errors.list')
					<form class="form" method="post" action="{{ route('permissions.store') }}">
						{{ csrf_field() }}
						<input type="hidden" name="_method" value="POST"/>
						<div class="row">
						<div class="form-group col-md-6">
							<label for="name"> @lang('equicare.name') </label>
							<input type="text" name="name" class="form-control"/>
						</div>
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