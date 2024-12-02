@extends('layouts.admin')
@section('body-title')
@lang('equicare.roles')
@endsection
@section('title')
| @lang('equicare.roles')
@endsection
@section('breadcrumb')
<li class="breadcrumb-item active">@lang('equicare.roles')</li>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">@lang('equicare.manage_roles')
					@if(Auth::user()->hasDirectPermission('Create Roles'))
					<a href="{{ route('roles.create') }}" class="btn btn-primary btn-flat">@lang('equicare.add_new')</a></h4>
					@endif
				</div>
				<div class="box-body table-responsive">
					<table class="table table-bordered table-hover dataTable bottom-padding" id="data_table">
						<thead class="thead-inverse">
							<tr>
								<th> # </th>
								<th> @lang('equicare.name') </th>
								<th> @lang('equicare.created_on') </th>
								<th> @lang('equicare.permissions') </th>
								@if(Auth::user()->hasDirectPermission('Edit Roles') || Auth::user()->hasDirectPermission('Delete Roles'))
								<th> @lang('equicare.action') </th>
								@endif
							</tr>
						</thead>
						<tbody>
							@if (isset($roles))
							@php
							$count = 0;
							@endphp
							@foreach ($roles as $role)
							@php
							$count++;
							@endphp
							<tr>
								<td> {{ $count }} </td>
								<td> {{ ucfirst($role->name) }} </td>
								<td> {{ $role->created_at->diffForHumans() }}</td>
								<td> {{ str_limit(implode(', ',$role->permissions->pluck('name')->toArray()),70) }} </td>
								@if (Auth::user()->can('Edit Roles') || Auth::user()->can('Delete Roles'))
								<td class="todo-list">
									<div class="tools">
										{!! Form::open(['url' => 'admin/roles/'.$role->id,'method'=>'DELETE','class'=>'form-inline']) !!}
										@if(Auth::user()->hasDirectPermission('Edit Roles'))
										<a href="{{ route('roles.edit',$role->id) }}" class="btn bg-purple btn-flat btn-sm" title="@lang('equicare.edit')"><i class="fa fa-edit"></i>  </a>
										@endif &nbsp;
										<input type="hidden" name="id" value="{{ $role->id }}">
										@if(Auth::user()->hasDirectPermission('Delete Roles'))
										<button class="btn btn-warning btn-flat btn-sm" type="submit" onclick="return confirm('@lang('equicare.are_you_sure')')" title="@lang('equicare.delete')"><span class="fa fa-trash-o" aria-hidden="true"></span></button>
										@endif
										{!! Form::close() !!}
									</div>
								</td>
								@endif
							</tr>
							@endforeach
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th> # </th>
								<th> @lang('equicare.name') </th>
								<th> @lang('equicare.created_on') </th>
								<th> @lang('equicare.permissions') </th>
								@if(Auth::user()->hasDirectPermission('Edit Roles') || Auth::user()->hasDirectPermission('Delete Roles'))
								<th> @lang('equicare.action') </th>
								@endif
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
	@endsection