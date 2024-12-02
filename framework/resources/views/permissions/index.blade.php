@extends('layouts.admin')
@section('body-title')
@lang('equicare.permissions')
@endsection
@section('title')
	| @lang('equicare.permissions')
@endsection
@section('breadcrumb')
	<li class=" active">@lang('equicare.permissions')</li>
@endsection
@section('content')
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
				<div class="box-header">
					<h4 class="box-title">@lang('equicare.manage_permissions')
							@can('Create Permissions')
								<a href="{{ route('permissions.create') }}" class="btn btn-primary btn-flat">@lang('equicare.add_new')</a>
							@endcan
						</h4>
					</div>
					<div class="box-body table-responsive">
						<table class="table table-bordered table-hover dataTable bottom-padding" id="data_table">
							<thead class="thead-inverse">
								<tr>
									<th> # </th>
									<th> @lang('equicare.name') </th>
									<th> @lang('equicare.created_on') </th>
									@if (\Auth::user()->can('Edit Permissions') || \Auth::user()->can('Delete Permissions'))
									<th> @lang('equicare.action')</th>
									@endif
								</tr>
							</thead>
							<tbody>
								@if (isset($permissions))
								@php
									$count = 0;
								@endphp
								@foreach ($permissions as $permission)
								@php
									$count++;
								@endphp
								<tr>
								<td> {{ $count }} </td>
								<td> {{ ucfirst($permission->name) }} </td>
								<td> {{ $permission->created_at->diffForHumans() }}</td>
								@if (\Auth::user()->can('Edit Permissions') || \Auth::user()->can('Delete Permissions'))
								<td class="todo-list">
									<div class="tools">
										{!! Form::open(['url' => 'admin/permissions/'.$permission->id,'method'=>'DELETE','class'=>'form-inline']) !!}
										@can('Edit Permissions')
											<a href="{{ route('permissions.edit',$permission->id) }}" class="btn bg-purple btn-flat btn-sm" title="@lang('equicare.edit')"><i class="fa fa-edit"></i>  </a>
										@endcan &nbsp;
										@can('Delete Permissions')
				                            <input type="hidden" name="id" value="{{ $permission->id }}">
				                            <button class="btn btn-warning btn-flat btn-sm" type="submit" onclick="return confirm('@lang('equicare.are_you_sure')')" title="@lang('equicare.delete')"><span class="fa fa-trash-o" aria-hidden="true"></span></button>
				                        @endcan
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
									@if (\Auth::user()->can('Edit Permissions') || \Auth::user()->can('Delete Permissions'))
									<th> @lang('equicare.action')</th>
									@endif
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
@endsection