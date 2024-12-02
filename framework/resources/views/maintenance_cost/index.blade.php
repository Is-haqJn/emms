@extends('layouts.admin')
@section('body-title')
@lang('equicare.maintenance_cost')
@endsection
@section('title')
| @lang('equicare.maintenance_cost')
@endsection
@section('breadcrumb')
<li class="active">@lang('equicare.maintenance_cost')</li>
@endsection
@section('content')
<div class="row">
        {{-- @dd(Auth::user()->permissions) --}}

	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">@lang('equicare.maintenance_cost')
					@if(Auth::user()->hasDirectPermission('Create Maintenance Cost'))
					<a href="{{ route('maintenance_cost.create') }}" class="btn btn-primary btn-flat">@lang('equicare.add_new')</a>
					@endif
				</h4>
			</div>
			<div class="box-body">
				<div class="table-responsive overflow_x_unset">
					<table id="data_table" class="table table-hover table-bordered table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th> @lang('equicare.hospital') </th>
								<th> @lang('equicare.cost_Type') </th>
								<th> @lang('equicare.by') </th>
								<th> @lang('equicare.action') </th>
							</tr>
						</thead>
						<tbody>
							@php $count=0; @endphp
							@if (isset($maintenance_costs))
							@foreach ($maintenance_costs as $cost)
							@php $count++; @endphp
							<tr>
								<td> {{ $count }} </td>
								<td> {{ ucwords($cost->hospital->name) }} </td>
								<td> {{ $cost->type=='amc'?'AMC':'CMC' }} </td>
								@php
								if($cost->cost_by =='tp'){
									$text = 'Third Party';
								}else{
									$text = isset(\App\Setting::first()->company)?\App\Setting::first()->company:config('app.name');
								}
								@endphp
								<td> {{ $text }} </td>
			                        <td >
										{!! Form::open(['url' => 'admin/maintenance_cost/'.$cost->id,'method'=>'DELETE','class'=>'form-inline']) !!}
										      @if(Auth::user()->hasDirectPermission('Edit Maintenance Cost'))
											<a href="{{ route('maintenance_cost.edit',$cost->id) }}" class="btn bg-purple btn-sm btn-flat" title="@lang('equicare.edit')"><i class="fa fa-edit"></i>  </a>
											@endif &nbsp;
				                            <input type="hidden" name="id" value="{{ $cost->id }}">
				                            <button class="btn btn-view btn-info btn-sm btn-flat" type="button" title="@lang('equicare.view')"  data-id="{{ $cost->id }}"><span class="fa fa-eye" aria-hidden="true"></span></button>
					      		    @if(Auth::user()->hasDirectPermission('Delete Maintenance Cost'))
				                            	<button class="btn btn-warning btn-sm btn-flat" type="submit" onclick="return confirm('@lang('equicare.are_you_sure')')" title="@lang('equicare.delete')"><span class="fa fa-trash-o" aria-hidden="true"></span></button>
				                            @endif
				                        {!! Form::close() !!}

									</td>

								@endforeach
								@endif
							</tbody>
							<tfoot>
								<tr>
									<th> # </th>
									<th> @lang('equicare.hospital') </th>
									<th> @lang('equicare.cost_Type') </th>
									<th> @lang('equicare.by') </th>
									<th> @lang('equicare.action') </th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="get_info"></div>

	@endsection
	@section('scripts')
	{{-- <script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script> --}}
	<script src="{{ asset('assets/js/datetimepicker.js') }}" type="text/javascript"></script>
	<script type="text/javascript">
	$(function(){
		$(document).on('click','.btn-view',function(){
			$btn = $(this);
			id = $btn.data('id');
			$.ajax({
			url:"{{ route('maintenance_cost.get_info') }}",
			method:"POST",
			data:{
				id:id,
				'_token':"{{ csrf_token() }}"
			},beforeSend:function(){
				$('#get_info_modal').modal('hide');
				$btn.prop('disabled',true);
			},complete:function(){
				$btn.prop('disabled',false);
			},success:function(res){
				if (res == 'not_exist') {
					alert('Error: Not Exist');
				}else{
					$('#get_info').html(res);
					$('#get_info_modal').modal('show');

				}
			},
			error:function(res){
				console.log(res);
			}
			})
		});
	})
	</script>

	@endsection
	@section('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}">
	<style type="text/css">
	.select2-container{
		display: block;
	}
</style>
@endsection