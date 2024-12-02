@extends('layouts.admin')
@section('body-title')
	@lang('equicare.roles')
@endsection
@section('title')
	| @lang('equicare.roles')
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('admin/roles') }}">@lang('equicare.roles')</a></li>
<li class="breadcrumb-item active">@lang('equicare.edit')</li>
@endsection
@section('styles')
	<style type="text/css">
	.checkbox{
		margin-top: 0px;
	}
	.checkbox+.checkbox, .radio+.radio {
    	margin-top: unset;
	}
	.row > .checkbox{
		padding-left: 0px;

	}
	.form-check > .row {
		margin-top: 20px;
	}
	</style>
@endsection
@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">@lang('equicare.edit_role')</h4>
				</div>
				<div class="box-body ">
					@include ('errors.list')
					<form class="form" method="post" action="{{ route('roles.update' , $role->id) }}">
						{{ csrf_field() }}
						<input type="hidden" name="_method" value="PATCH"/>
						<div class="row">
						<div class="form-group col-md-6">
							<label for="name"> @lang('equicare.name') </label>
							<input type="text" name="name" value="{{ $role->name }}" class="form-control"/>
						</div>
						{{-- <div class="form-group col-md-12">
							<label for="permissions[]" > @lang('equicare.permissions') </label>
							<div class="form-check">
								<input type="checkbox" name="check_all" id="check_all"/>
								<label for="check_all"> @lang('equicare.check_all')</label>
								<br/> --}}
							{{-- @if (isset($permissions))
							<div class="row">
							@foreach ($permissions as $permission)
							<div class="col-md-4 checkbox">
								<label for="permissions[]" class="form-check-label">
								<input type="checkbox" name="permissions[]" class="" @if(in_array($permission->id, $role->permissions->pluck('id')->toArray()))
										  {{ "checked" }}
								  @endif  value="{{ $permission->id }}" />
								{{ $permission->name }}  </label><br/>
							</div>
								@endforeach
							</div>
								@endif --}}
								@if (isset($permissions) && $permissions->count() > 0)
                               @include('user_role_permission.table_edit_role',['data'=>$role])
            		@endif  
							</div>
						{{-- </div> --}}
						<div class="form-group col-md-6">
							<input type="submit" value="@lang('equicare.submit')" class="btn btn-primary btn-flat"/>
						</div>
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>
@endsection