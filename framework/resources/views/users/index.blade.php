@extends('layouts.admin')
@section('body-title')
@lang('equicare.users')
@endsection
@section('title')
	| @lang('equicare.users')
@endsection
@section('breadcrumb')
<li class="breadcrumb-item active">@lang('equicare.users')</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
			<div class="box-header">
					<h4 class="box-title">@lang('equicare.manage_users')
					   @if(Auth::user()->hasDirectPermission('Create Users'))
								<a href="{{ route('users.create') }}" class="btn btn-primary btn-flat">@lang('equicare.add_new')</a>
						@endif	
					</h4>
				</div>
				<div class="box-body table-responsive">
					<table class="table table-bordered table-hover dataTable bottom-padding" id="data_table">
						<thead class="thead-inverse">
							<tr>
								<th> # </th>
								<th> @lang('equicare.name') </th>
								<th> @lang('equicare.email') </th>
								<th> @lang('equicare.created_on') </th>
								<th> @lang('equicare.role') </th>
								@if(Auth::user()->hasDirectPermission('Edit Users') || Auth::user()->hasDirectPermission('Delete Users'))
								<th> @lang('equicare.action')</th>
								@endif
							</tr>
						</thead>
						<tbody>
							@if (isset($users))
							@php
								$count = 0;
							@endphp
							@foreach ($users as $user)
							@php
								$count++;
							@endphp
							<tr>
							<td> {{ $count }} </td>
							<td> {{ ucfirst($user->name) }} </td>
							<td> {{  $user->email }}</td>
							<td> {{ $user->created_at->diffForHumans() }}</td>
							<td> {{ $user->roles->pluck('name')->toArray()[0] ?? '' }} </td>
							@if(Auth::user()->hasDirectPermission('Edit Users') || Auth::user()->hasDirectPermission('Delete Users'))
                        <td class="todo-list">
								<div class="tools">
									{!! Form::open(['url' => 'admin/users/'.$user->id,'method'=>'DELETE','class'=>'form-inline']) !!}
									   @if(Auth::user()->hasDirectPermission('Edit Users'))
										<a href="{{ route('users.edit',$user->id) }}" class="btn bg-purple btn-sm btn-flat" title="@lang('equicare.edit')"><i class="fa fa-edit"></i>  </a>
										@endif &nbsp;
			                            <input type="hidden" name="id" value="{{ $user->id }}">
			                           @if(Auth::user()->hasDirectPermission('Delete Users'))
			                            <button class="btn btn-warning btn-sm btn-flat" type="submit" onclick="return confirm('@lang('equicare.are_you_sure')')" title="@lang('equicare.delete')"><span class="fa fa-trash-o" aria-hidden="true"></span></button>
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
								<th> @lang('equicare.email') </th>
								<th> @lang('equicare.created_on') </th>
								<th> @lang('equicare.role') </th>
								@if(Auth::user()->hasDirectPermission('Edit Users') || Auth::user()->hasDirectPermission('Delete Users'))
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