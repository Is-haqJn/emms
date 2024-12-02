@extends('layouts.admin')
@section('body-title')
	@lang('equicare.hospitals')
@endsection
@section('title')
	| @lang('equicare.hospitals')
@endsection
@section('breadcrumb')
<li class="active">@lang('equicare.hospitals')</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">@lang('equicare.manage_hospitals')
						@if(
							Auth::user()->hasDirectPermission('Create Hospitals'))
							<a href="{{ route('hospitals.create') }}" class="btn btn-primary btn-flat">@lang('equicare.add_new')</a></h4>
						@endif

				</div>

				<div class="box-body table-responsive">
					<table class="table table-bordered table-hover dataTable bottom-padding" id="data_table">
						<thead class="thead-inverse">
							<tr>
								<th> # </th>
								<th> @lang('equicare.name') </th>
								<th> @lang('equicare.email') </th>
								<th> @lang('equicare.user') </th>
								<th> @lang('equicare.slug') </th>
								<th> @lang('equicare.phone') </th>
								<th> @lang('equicare.mobile') </th>
								@if(Auth::user()->hasDirectPermission('Edit Hospitals') || Auth::user()->hasDirectPermission('Delete Hospitals'))
								<th> @lang('equicare.action')</th>
								@endif
							</tr>
						</thead>
						<tbody>
							@if (isset($hospitals))
							@php
								$count = 0;
							@endphp
							@foreach ($hospitals as $hospital)
							@php
								$count++;
							@endphp
							<tr>
							<td> {{ $count }} </td>
							<td> {{ ucfirst($hospital->name) }} </td>
							<td> {{  $hospital->email ?? '-' }}</td>
							<td> {{ $hospital->user ? ucfirst($hospital->user->name) : '-' }}</td>
							<td> {{ $hospital->slug ?? '-' }}</td>
							<td> {{ $hospital->phone_no ?? '-'}} </td>
							<td> {{ $hospital->mobile_no ?? '-'}} </td>
							@if(
							Auth::user()->hasDirectPermission('Edit Hospitals') || Auth::user()->hasDirectPermission('Delete Hospitals'))
                        	<td class="text-nowrap">
								{!! Form::open(['url' => 'admin/hospitals/'.$hospital->id,'method'=>'DELETE','class'=>'form-inline']) !!}
									{{-- @can('Edit Hospitals') --}}
									@if(Auth::user()->hasDirectPermission('Edit Hospitals'))
									<a href="{{ route('hospitals.edit',$hospital->id) }}" class="btn bg-purple btn-sm btn-flat" title="@lang('equicare.edit')"><i class="fa fa-edit"></i>  </a>
									{{-- @endcan  --}}
									&nbsp;
		                            @endif
		                            <input type="hidden" name="id" value="{{ $hospital->id }}">
									@if(Auth::user()->hasDirectPermission('Delete Hospitals'))

		                            {{-- @can('Delete Hospitals') --}}
		                            <button class="btn btn-warning btn-sm btn-flat" type="submit" onclick="return confirm('@lang('equicare.are_you_sure')')" title="@lang('equicare.delete')"><span class="fa fa-trash-o" aria-hidden="true"></span></button>
		                            {{-- @endcan --}}
		                            @endif
		                        {!! Form::close() !!}
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
								<th> @lang('equicare.user') </th>
								<th> @lang('equicare.slug') </th>
								<th> @lang('equicare.phone') </th>
								<th> @lang('equicare.mobile') </th>
								@if(Auth::user()->hasDirectPermission('Edit Hospitals') || Auth::user()->hasDirectPermission('Delete Hospitals'))
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