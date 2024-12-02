@extends('layouts.admin')
	@section('body-title')
	@lang('equicare.equipments')
	@endsection
	@section('title')
	| @lang('equicare.equipments')
	@endsection
	@section('breadcrumb')
	<li class="active">@lang('equicare.equipments')</li>
	@endsection

	@section('content')
	<div class="row">
	    <div class="col-md-12">
	        <div class="box box-primary">
	            <div class="box-header with-border">
	                <h4 class="box-title">@lang('equicare.filters')</h4>
	            </div>
	            <div class="box-body">
	                <form method="get" class="form" action="{{ route('equipments.index') }}">
	                    <div class="row">
	                        <div class="form-group col-md-3">
	                            <label>@lang('equicare.hospital'): </label>
	                            <select name="hospital_id" class="form-control">
	                                <option value="">@lang('equicare.select')</option>
	                                @if(isset($hospitals))
	                                @foreach ($hospitals as $hospital)
	                                <option value="{{ $hospital->id }}" @if(isset($hospital_id) &&
	                                    $hospital_id==$hospital->id)
	                                    selected
	                                    @endif
	                                    >
	                                    {{ ucfirst($hospital->name) }}
	                                </option>
	                                @endforeach
	                                @endif
	                            </select>
	                        </div>
								<div class="form-group col-md-3">
									<label>@lang('equicare.department'): </label>
									<select name="department_id" class="form-control">
										<option value="">@lang('equicare.select')</option>
										@if(isset($departments))
											@foreach ($departments as $department)
												<option value="{{ $department->id }}" @if(isset($department_id) && $department_id == $department->id) selected @endif>
												{{$department->short_name}} ({{ ucfirst($department->name) }})
												</option>
											@endforeach
										@endif
									</select>
								</div>

	                        <div class="form-group col-md-3">
	                            <label>@lang('equicare.company'): </label>
	                            <select name="company" class="form-control">
	                                <option value="">@lang('equicare.select')</option>
	                                @if(isset($companies))
	                                @foreach ($companies as $company)
	                                <option value="{{ $company->company }}" @if(isset($companyy) && $companyy==$company->
	                                    company)
	                                    selected
	                                    @endif
	                                    >
	                                    {{ ucfirst($company->company) }}
	                                </option>
	                                @endforeach
	                                @endif
	                            </select>
	                        </div>
	                        <div class="form-group col-md-2">
	                            <label class="visibility">123</label>
	                            <input type="submit" value="excel" id="excel_hidden" name="excel_hidden" class="hidden" />
	                            <input type="submit" value="pdf" id="pdf_hidden" name="pdf_hidden" class="hidden" />
	                            <input type="submit" value="@lang('equicare.submit')"
	                                class="btn btn-primary btn-flat form-control" />
	                        </div>
	                    </div>
	                </form>
	            </div>
	        </div>
	        <div class="box box-primary">
	            <div class="box-header with-border">
	                <h4 class="box-title">@lang('equicare.manage_equipments')
	                    @if (\Auth::user()->hasDirectPermission('Create Equipments'))
	                    <a href="{{ route('equipments.create') }}"
	                        class="btn btn-primary btn-flat">@lang('equicare.add_new')</a>
	                </h4>
	                @endif
	                <div class="export-btns">
	                    <!-- {!! Form::label('excel_hidden',__('equicare.export_excel'),['class' => 'btn btn-success btn-flat excel','name'=>'action','tabindex'=>1]) !!}
					{!! Form::label('pdf_hidden',__('equicare.export_pdf'),['class' => 'btn btn-primary btn-flat pdf','name'=>'action','tabindex'=>2]) !!} -->
	                    <label class="btn btn-success btn-flat export-excel" name="excel_hidden" data-toggle="modal"
	                        data-target="#exportModal">@lang('equicare.export_excel')</label>

	                    <label class="btn btn-primary btn-flat" name="pdf_hidden" data-toggle="modal"
	                        data-target="#exportpdfmodal">@lang('equicare.export_pdf')</label>
	                </div>
	            </div>
	            <div class="box-body">
	                <div class="table-responsive">
	                    <table class="table table-bordered table-hover dataTable bottom-padding" id="data_table_equipment">
	                        <thead class="thead-inverse">
	                            <tr>
	                                <th> # </th>
	                                <th> @lang('equicare.qr_code') </th>
	                                <th> @lang('equicare.name') </th>
	                                <th> @lang('equicare.short_name') </th>
	                                <th> @lang('equicare.user') </th>
	                                <th> @lang('equicare.company') </th>
	                                <th> @lang('equicare.model') </th>
	                                <th> @lang('equicare.hospital') </th>
	                                <th> @lang('equicare.serial_no') </th>
	                                <th> @lang('equicare.department') </th>
	                                <th> @lang('equicare.unique_id') </th>
	                                <th> @lang('equicare.purchase_date') </th>
	                                <th> @lang('equicare.order_date') </th>
	                                <th> @lang('equicare.installation_date') </th>
	                                <th> @lang('equicare.warranty_date') </th>
	                                @if(Auth::user()->hasDirectPermission('Edit Equipments') ||
	                                Auth::user()->hasDirectPermission('Delete Equipments'))
	                                <th> @lang('equicare.action') </th>
	                                @endif
	                            </tr>
	                        </thead>
	                        <tbody>
	                            @if (isset($equipments))
	                            {{-- @dd($equipments) --}}
	                            @foreach ($equipments as $key => $equipment)
	                            <tr>
	                                <td> {{ $key+1 }} </td>
	                                @php
	                                $qrGenerate = \App\QrGenerate::where('id', $equipment->qr_id)->first();
	                                $uid = $qrGenerate ? $qrGenerate->uid : '';
	                                $u_e_id = (\App\QrGenerate::where('id',$equipment->qr_id)->first() !=null ?
	                                (\App\QrGenerate::where('id',$equipment->qr_id)->first()->uid) : '')
	                                @endphp
	                                <td><img loading="lazy" src="{{ asset('/uploads/qrcodes/qr_assign/'.$u_e_id.'.png') }}"
	                                        width="80px" /></td>
	                                <td> {{ ucfirst($equipment->name) }} </td>
	                                <td>{{ $equipment->short_name }}</td>
	                                <td>{{ $equipment->user?ucfirst($equipment->user->name):'-' }}</td>
	                                <td>{{ $equipment->company?? '-' }}</td>
	                                <td>{{ $equipment->model ?? '-' }}</td>
	                                <td>{{ $equipment->hospital?$equipment->hospital->name:'-' }}</td>
	                                <td>{{ $equipment->sr_no }}</td>
	                                {{-- {{dd($equipment->get_department)}} --}}
	                                <td>{{($equipment->get_department->short_name)??"-" }}
	                                    ({{ ($equipment->get_department->name) ??'-' }})</td>
	                                @php
	                                $uids = explode('/',$equipment->unique_id);
	                                $department_id = $uids[1];
	                                $department = \App\Department::withTrashed()->find($department_id);
	                                if (!is_null($department)) {
	                                $uids[1] = $department->short_name;
	                                }
	                                $uids = implode('/',$uids);
	                                @endphp
	                                {{-- <td>{{ $uids }}</td> --}}
	                                <td>{{$equipment->unique_id ?? ''}}</td>
	                                <td>{{ date_change($equipment->date_of_purchase)?? '-' }}</td>
	                                <td>{{ date_change($equipment->order_date)?? '-' }}</td>
	                                <td>{{ date_change($equipment->date_of_installation)??'-' }}</td>
	                                <td>{{ date_change($equipment->warranty_due_date)??'-' }}</td>
	                                @if(Auth::user()->hasDirectPermission('Edit Equipments') ||
	                                Auth::user()->hasDirectPermission('Delete Equipments'))
	                                <td class="text-nowrap">
	                                    {!! Form::open(['url' =>
	                                    'admin/equipments/'.$equipment->id,'method'=>'DELETE','class'=>'form-inline']) !!}
	                                    @if(Auth::user()->hasDirectPermission('Edit Equipments'))
	                                    <a href="{{ route('equipments.edit',$equipment->id) }}"
	                                        class="btn bg-purple btn-sm btn-flat marginbottom"
	                                        title="@lang('equicare.edit')"><i class="fa fa-edit"></i></a>
	                                    @endif
	                                    <a target="_blank" href="{{ route('equipments.history',$equipment->id) }}"
	                                        class="btn bg-success btn-sm btn-flat marginbottom"
	                                        title="@lang('equicare.history')"><i class="fa fa-history"></i></a>
	                                    @php
	                                    // \App\Equipment::select('*')->delete();
	                                    $u_e_id = (\App\QrGenerate::where('id',$equipment->qr_id)->first() !=null ?
	                                    (\App\QrGenerate::where('id',$equipment->qr_id)->first()->uid) : '')

	                                    @endphp
	                                    <a href="#" class="btn bg-success btn-sm btn-flat marginbottom"
	                                        title="@lang('equicare.qr_code')" data-uniqueid="{{$equipment->unique_id}}"
	                                        data-url="{{ asset('uploads/qrcodes/qr_assign/'.$u_e_id.'.png') }}"
	                                        data-toggle="modal" data-target="#qr-modal"><i class="fa fa-qrcode"></i></a>
	                                    <input type="hidden" name="id" value="{{ $equipment->id }}">
	                                    @if(Auth::user()->hasDirectPermission('Delete Equipments'))
	                                    <button class="btn btn-warning btn-sm btn-flat marginbottom" type="submit"
	                                        onclick="return confirm('@lang('equicare.are_you_sure')')"
	                                        title="@lang('equicare.delete')"><span class="fa fa-trash-o"
	                                            aria-hidden="true"></span></button>
	                                    @endif
	                                    {!! Form::close() !!}
	                                </td>
	                                @endif

	                                @endforeach
	                                @endif
	                            </tr>
	                        </tbody>
	                        <tfoot>
	                            <tr>
	                                <th> # </th>
	                                <th> @lang('equicare.qr_code') </th>
	                                <th> @lang('equicare.name') </th>
	                                <th> @lang('equicare.short_name') </th>
	                                <th> @lang('equicare.user') </th>
	                                <th> @lang('equicare.company') </th>
	                                <th> @lang('equicare.model') </th>
	                                <th> @lang('equicare.hospital') </th>
	                                <th> @lang('equicare.serial_no') </th>
	                                <th> @lang('equicare.department') </th>
	                                <th> @lang('equicare.unique_id') </th>
	                                <th> @lang('equicare.purchase_date') </th>
	                                <th> @lang('equicare.order_date') </th>
	                                <th> @lang('equicare.installation_date') </th>
	                                <th> @lang('equicare.warranty_date') </th>
	                                @if(Auth::user()->hasDirectPermission('Edit Equipments') ||
	                                Auth::user()->hasDirectPermission('Delete Equipments'))
	                                <th> @lang('equicare.action') </th>
	                                @endif
	                            </tr>
	                        </tfoot>
	                    </table>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>


	<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exportModalLabel">@lang('equicare.export_excel')</h5>
            </div>
            <div class="modal-body">
                <form id="exportForm" method="post" action="{{url('export-to-excel')}}">
                    @csrf
                    <div class="form-group">
                        <label for="title">@lang('equicare.title')</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ isset($exportbackup->title) ? $exportbackup->title : '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="subtitle">@lang('equicare.subtitle')</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle"
                            value="{{ isset($exportbackup->subtitle) ? $exportbackup->subtitle : '' }}" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('equicare.list_columns')</label><br>
                        <div class="row" id="columns-container" style="margin-left:-7px;">

                            @if ($exportbackup)
                            @php
                            $exportColumns = json_decode($exportbackup->columns, true);
                            if ($exportColumns === null) {
                            $exportColumns = array_keys($columns);
                            }
                            @endphp

                            @foreach ($columns as $key => $label)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $key }}" id="{{ $key }}"
                                        name="columns[]" {{ in_array($key, $exportColumns) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="row">
                                @foreach($columns as $key => $label)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $key }}"
                                            id="{{ $key }}" name="columns[]" checked>
                                        <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <!-- @if ($errors->has('columns'))
                            <div id="column-error" style="color: red;">
                                {{ $errors->first('columns') }}
                            </div>
                            @endif -->
                            @endif
							<div id="column-error" style="color:red;">

							</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">@lang('equicare.close')</button>
                        <button type="submit" class="btn btn-primary" id="exportButton">@lang('equicare.export')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

	<div class="modal fade {{ $errors->any() ? 'show' : '' }}" id="exportpdfmodal" tabindex="-1" role="dialog"
	    aria-labelledby="exportpdfModalLabel" aria-hidden="{{ $errors->any() ? 'false' : 'true' }}"
	    style="{{ $errors->any() ? 'display: block;' : '' }}">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="close">
	                    &times;
	                </button>
	                <h4 class="modal-title" id="exportpdfModalLabel">@lang('equicare.export_pdf')</h4>
	            </div>
	            <div class="modal-body">
	                <form method="post" action="{{ url('export-to-pdf') }}" id="exportpdfform">
	                    @csrf
	                    <div class="form-group">
	                        <label class="form-label" for="title">@lang('equicare.title')</label>
	                        <input type="text" class="form-control" name="title"
	                            value="{{ old('title', isset($exportpdfbackup->title) ? $exportpdfbackup->title : '') }}"
	                            required>
	                        @if ($errors->has('title'))
	                        <div class="text-danger">{{ $errors->first('title') }}</div>
	                        @endif
	                    </div>
	                    <div class="form-group">
	                        <label class="form-label" for="subtitle">@lang('equicare.subtitle')</label>
	                        <input type="text" class="form-control" name="subtitle"
	                            value="{{ old('subtitle', isset($exportpdfbackup->subtitle) ? $exportpdfbackup->subtitle : '') }}"
	                            required>
	                        @if ($errors->has('subtitle'))
	                        <div class="text-danger">{{ $errors->first('subtitle') }}</div>
	                        @endif
	                    </div>
	                    <div class="form-group">
	                        <label class="form-label" for="columns">@lang('equicare.list_columns')</label>
	                        <div class="row">
	                            @if (isset($exportpdfbackup))
	                            @php
	                            $exportColumns = json_decode($exportpdfbackup->columns, true);
	                            if ($exportColumns === null) {
	                            $exportColumns = array_keys($columns);
	                            }
	                            @endphp
	                            @foreach ($columns_pdf as $key => $label)
	                            <div class="col-md-6">
	                                <div class="form-check">
	                                    <input class="form-check-input" type="checkbox" value="{{ $key }}" id="{{ $key }}"
	                                        name="columns[]"
	                                        {{ in_array($key, old('columns', $exportColumns)) ? 'checked' : '' }}>
	                                    <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
	                                </div>
	                            </div>
	                            @endforeach
	                            @else
	                            @foreach($columns_pdf as $key => $label)
	                            <div class="col-md-6">
	                                <div class="form-check">
	                                    <input class="form-check-input" type="checkbox" value="{{ $key }}" id="{{ $key }}"
	                                        name="columns[]" checked>
	                                    <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
	                                </div>
	                            </div>
	                            @endforeach
	                            @endif
	                        </div>
	                        @if ($errors->has('columns'))
	                        <div class="text-danger">{{ $errors->first('columns') }}</div>
	                        @endif
	                    </div>
	                    <div class="modal-footer">
	                        <button type="button" class="btn btn-secondary"
	                            data-dismiss="modal">@lang('equicare.close')</button>
	                        <button type="submit" class="btn btn-primary" id="exportPdfButton">@lang('equicare.export')</button>
	                    </div>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>

	@endsection
	@section('scripts')


	<script type="text/javascript">
$(document).ready(function() {
	@if($errors -> any())
    $('#exportpdfmodal').modal('show');
    @endif
	$('#exportModal').on('hidden.bs.modal', function () {
    $('.modal-backdrop').remove();  	
});
	$('#exportForm').submit(function(event) {
		console.log("form Submited");
            if ($('#columns-container input[type="checkbox"]:checked').length === 0) {
                event.preventDefault(); 	
				console.log("dsfdfd");
				$('#column-error').html('Please check at least one column.').css('color', 'red');
                $('#exportModal').modal('show'); 
				$('.modal-backdrop').remove();
            } else {
				$('#column-error').html('');
                $('#exportModal').modal('hide');
				$('.modal-backdrop').remove();
            }
        });

    $('#data_table_equipment').DataTable();
    $('#qr-modal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var modal = $(this)
        modal.find('#qr-modal-iframe').attr('src', '#');
        modal.find('.modal-title').html('QR Code for <strong>' + button.data('uniqueid') + '</strong>');
        modal.find('#qr-image').attr('src', button.data('url'));
    });
});
	</script>
	@endsection
	@section('styles')
	<style type="text/css">
#data_table_equipment {
    border-collapse: collapse !important;
}

.export-btns {
    display: inline-block;
    float: right;
}
	</style>
	<div class="modal fade" id="qr-modal" tabindex="-1" role="dialog">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
	                        aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title"></h4>
	            </div>
	            <div class="modal-body">
	                <div class="text-center">
	                    <!-- <iframe id="qr-modal-iframe" src="" width="100%" height="170" style="border:0; overflow:hidden;"></iframe> -->
	                    <img id="qr-image" src="" alt="" />
	                </div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	            </div>
	        </div><!-- /.modal-content -->
	    </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	@endsection