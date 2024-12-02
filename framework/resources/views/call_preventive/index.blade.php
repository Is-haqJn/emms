@extends('layouts.admin')
@section('body-title')
    @lang('equicare.call_entries')
@endsection
@section('title')
    | @lang('equicare.preventive_maintenance_call_entry')
@endsection
@section('breadcrumb')
    <li class="active">@lang('equicare.preventive_maintenance')</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h4 class="box-title">@lang('equicare.preventive_maintenance')
                        @if(Auth::user()->hasDirectPermission('Create Preventive Maintenance'))
                            <a href="{{ route('preventive_maintenance.create') }}"
                                class="btn btn-primary btn-flat">@lang('equicare.add_new')</a>
                        @endif
                    </h4>
                    <div class="export-btns" style="display:inline-block;float:right;">
					<a class="btn btn-primary" href="{{route('preventive.export','excel')}}" target="_blank">@lang('equicare.export_excel')</a>
					<a class="btn btn-success" href="{{route('preventive.export','pdf')}}">@lang('equicare.export_pdf')</a>
					</div>
                </div>
                <div class="box-body">
                    <div class="table-responsive overflow_x_unset">
                        <table id="data_table" class="table table-hover table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th> @lang('equicare.equipment_name') </th>
                                    <th> @lang('equicare.user') </th>
                                    <th> @lang('equicare.call_handle') </th>
                                    <th> @lang('equicare.working_status') </th>
                                    <th> @lang('equicare.report_number') </th>
                                    <th> @lang('equicare.call_registration_date_time')</th>
                                    <th> @lang('equicare.next_due_date')</th>
                                    <th> @lang('equicare.attended_by') </th>
                                    <th> @lang('equicare.first_attended_on') </th>
                                    <th> @lang('equicare.completed_on') </th>
                                    <th> @lang('equicare.action') </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $count=0; @endphp
                                @if (isset($p_maintenance))
                                    @foreach ($p_maintenance as $preventive)
                                        @php $count++; @endphp
                                        <tr>
                                            <td> {{ $count }} </td>
                                            <td> {{ $preventive->equipment->name ?? '-' }} </td>
                                            <td> {{ $preventive->user->name ?? '-' }}</td>
                                            <td> {{ $preventive->call_handle ? ucfirst($preventive->call_handle) : '-' }} </td>
                                            <td> {{ $preventive->working_status ? ucfirst($preventive->working_status) : '-' }}
                                            </td>
                                            <td> {{ $preventive->report_no ? sprintf('%04d', $preventive->report_no) : '-' }}
                                            </td>
                                            <td>
                                                {{ $preventive->call_register_date_time ? date('Y-m-d h:i A', strtotime($preventive->call_register_date_time)) : '-' }}
                                            </td>
                                            <td> {{ date_change($preventive->next_due_date) ?? '-' }}</td>
                                            <td>{{ $preventive->user_attended_fn ? $preventive->user_attended_fn->name : '-' }}
                                            </td>
                                            <td>{{ $preventive->user_attended_fn ? date('Y-m-d H:i A', strtotime($preventive->call_attend_date_time)) : '-' }}
                                            </td>
                                            <td>{{ $preventive->call_complete_date_time ? date('Y-m-d H:i A', strtotime($preventive->call_complete_date_time)) : '-' }}
                                            </td>
                                            <td>

                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm"
                                                        data-toggle="dropdown" aria-expanded="true">
                                                        <span class="fa fa-cogs"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>

                                                    <ul class="dropdown-menu custom" role="menu">
                                                        @if(Auth::user()->hasDirectPermission('Edit Preventive Maintenance'))
                                                            <li>
                                                                <a href="{{ route('preventive_maintenance.edit', $preventive->id) }}"
                                                                    class="" title="@lang('equicare.edit')"><i
                                                                        class="fa fa-edit purple-color"></i> @lang('equicare.edit')
                                                                </a>
                                                            </li>
                                                            @endif
                                                        <li>
                                                            @if (is_null($preventive->call_attend_date_time))
                                                                <a href="#attend_modal" data-target="#attend_modal"
                                                                    data-toggle="modal" title="@lang('equicare.attend_call')"
                                                                    class="attend_btn"
                                                                    data-status="{{ $preventive->working_status }}"
                                                                    data-id="{{ $preventive->id }}">
                                                                    <i class="fa fa-list-alt yellow-color"></i>
                                                                    @lang('equicare.attend_call')
                                                                </a>
                                                            @endif
                                                        </li>
                                                        @if (!is_null($preventive->call_attend_date_time) && is_null($preventive->call_complete_date_time))
                                                            <li>
                                                                <a href="#call_complete_modal"
                                                                    data-target="#call_complete_modal" data-toggle="modal"
                                                                    title="@lang('equicare.call_complete')" class="call_complete_btn"
                                                                    data-status="{{ $preventive->working_status }}"
                                                                    data-id="{{ $preventive->id }}">
                                                                    <i class="fa fa-thumbs-o-up green-color"></i>
                                                                    @lang('equicare.call_complete')
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if(Auth::user()->hasDirectPermission('Delete Preventive Maintenance'))
                                                            <li>
                                                                <a class=""
                                                                    href="javascript:document.getElementById('form1').submit();"
                                                                    onclick="return confirm('@lang('equicare.are_you_sure')')"
                                                                    title="@lang('equicare.delete')"><span
                                                                        class="fa fa-trash-o red-color"
                                                                        aria-hidden="true"></span>
                                                                    @lang('equicare.delete')
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                                {!! Form::open([
                                                    'url' => 'admin/call/preventive_maintenance/' . $preventive->id,
                                                    'method' => 'DELETE',
                                                    'id' => 'form1',
                                                    'class' => 'form-horizontal',
                                                ]) !!}
                                                <input type="hidden" id="id" name="id"
                                                    value="{{ $preventive->id }}">
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th> @lang('equicare.equipment_name') </th>
                                    <th> @lang('equicare.user') </th>
                                    <th> @lang('equicare.call_handle') </th>
                                    <th> @lang('equicare.working_status') </th>
                                    <th> @lang('equicare.report_number') </th>
                                    <th> @lang('equicare.next_due_date')</th>
                                    <th> @lang('equicare.call_registration_date_time')</th>
                                    <th> @lang('equicare.attended_by') </th>
                                    <th> @lang('equicare.first_attended_on') </th>
                                    <th> @lang('equicare.completed_on') </th>
                                    <th> @lang('equicare.action') </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Attend call modal ======================================= --}}
    <div class="modal fade" id="attend_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open([
                    'route' => 'preventive_attend_complete',
                    'method' => 'POST',
                    'class' => 'attend_call_form',
                    'id' => 'attend_call_form',
                ]) !!}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('equicare.attend_call')</h4>
                </div>
                <div class="modal-body">
                    @if (count($errors->attend_call) > 0)
                        <div class="row">
                            <div class="col-md-8">
                                <div class="alert alert-danger">
                                    <ul class=" mb-0">
                                        @foreach ($errors->attend_call->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif


                    <div class="row">

                        <div class="form-group col-md-6">
                            {!! Form::label('call_attend_date_time', __('equicare.call_attend_date_time')) !!}
                            {!! Form::text('call_attend_date_time', null, ['class' => 'form-control call_attend_date_time']) !!}
                            {{ Form::hidden('b_id', null, ['class' => 'b_id']) }}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('user', __('equicare.user_attended')) !!}
                            {!! Form::select('user_attended', $users, null, [
                                'placeholder' => 'select user',
                                'class' => 'form-control
                            						user_attended',
                            ]) !!}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('service_rendered', __('equicare.service_rendered')) !!}
                            {!! Form::text('service_rendered', null, ['class' => 'form-control service_rendered']) !!}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('remarks', __('equicare.remarks')) !!}
                            {!! Form::textarea('remarks', null, ['class' => 'form-control remarks', 'rows' => 2]) !!}
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('equicare.working_status')</label>
                            {!! Form::select(
                                'working_status',
                                [
                                    'working' => __('equicare.working'),
                                    'not working' => __('equicare.not_working'),
                                    'pending' => __('equicare.pending'),
                                ],
                                null,
                                ['placeholder' => '--select--', 'class' => 'form-control test working_status'],
                            ) !!}
                        </div>
                        <input type="hidden" name="id" class="id" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('equicare.submit'), ['class' => 'btn btn-primary submit_attend btn-sm']) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('equicare.close')</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    {{-- call complete modal======================================= --}}
    <div class="modal fade" id="call_complete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['method' => 'post', 'route' => 'preventive_call_complete', 'files' => true]) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('equicare.complete_call')</h4>
                </div>
                <div class="modal-body">
                    @if (count($errors->complete_call) > 0)
                        <div class="row">
                            <div class="col-md-8">
                                <div class="alert alert-danger">
                                    <ul class=" mb-0">
                                        @foreach ($errors->complete_call->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="form-group col-md-6">
                            {!! Form::label('call_complete_date_time', __('equicare.call_complete_date_time')) !!}
                            {!! Form::text('call_complete_date_time', null, ['class' => 'form-control call_complete_date_time']) !!}
                        </div>
                        <div class="form-group col-md-6">
                            <label for="next_due_date">
                                @lang('equicare.next_due_date')
                            </label>
                            <div class="input-group">
                                {!! Form::text('next_due_date', null, ['class' => ['form-control', 'next_due_date']]) !!}
                                <span class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('service_rendered', __('equicare.service_rendered')) !!}
                            {!! Form::select(
                                'service_rendered',
                                [
                                    'Cleaning of filters' => __('equicare.cleaning_filters'),
                                    'Check electrics and wiring' => __('equicare.electrics_wiring'),
                                    'Internal Cleaning' => __('equicare.internal_cleaning'),
                                    'Display Working' => __('equicare.display_working'),
                                    'Parts replaced accounrding to the manufacturer guidlines' => __(
                                        'equicare.parts_replaced_accounrding_manufacturer_guidlines',
                                    ),
                                    'Other Parameters' => __('equicare.other_parameters'),
                                    'add_new' => '+' . __('equicare.add_new'),
                                ],
                                null,
                                ['placeholder' => __('equicare.select_option'), 'class' => 'form-control test service_rendered_select2'],
                            ) !!}
                        </div>
                        <div class="form-group col-md-6">
                            <label>&nbsp;</label>
                            <input type="text" name="new_item" value=""
                                class="new_item form-control none-display" placeholder="@lang('equicare.enter_service')" />
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group col-md-6">
                            <label>@lang('equicare.working_status')</label>
                            {!! Form::select(
                                'working_status',
                                [
                                    'working' => __('equicare.working'),
                                    'not working' => __('equicare.not_working'),
                                    'pending' => __('equicare.pending'),
                                ],
                                null,
                                ['placeholder' => '--select--', 'class' => 'form-control test working_status'],
                            ) !!}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('remarks', __('equicare.remarks')) !!}
                            {!! Form::textarea('remarks', null, ['class' => 'form-control remarks', 'rows' => 2]) !!}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('sign_of_engineer', __('equicare.sign_of_engineer')) !!}
                            {!! Form::file('sign_of_engineer', [
                                'class' => 'form-control sign_of_engineer',
                                'id' => 'sign_of_engineer',
                            ]) !!}
                            {{ Form::hidden('b_id', null, ['class' => 'b_id']) }}
                            <!-- <a class="view_image_sign_of_engineer" href="" target="_blank">
                                view
                            </a> -->
                            {{-- @if(isset($breakdown_c))
                            <a class="view_image_sign_of_engineer"
                                href="{{$breakdown_c->sign_of_engineer !=null?asset('/uploads/',$breakdown_c->sign_of_engineer) :'' }}"
                                target="_blank">
                                view
                            </a>
                            @endif --}}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('sign_stamp_of_incharge', __('equicare.sign_stamp_of_incharge')) !!}
                            {!! Form::file('sign_stamp_of_incharge', [
                                'class' => 'form-control sign_stamp_of_incharge',
                                'id' => 'sign_stamp_of_incharge',
                            ]) !!}
                            {{-- @if(isset($breakdown_c))
                           	<a class="view_image_sign_stamp_of_incharge" href="{{ $breakdown_c->sign_stamp_of_incharge !=null?asset('uploads/',$breakdown_c->sign_stamp_of_incharge) :'' }}" target="_blank">
							view
						  </a>
                          @endif --}}
                        </div>
                        <input type="hidden" name="id" class="id" value="">

                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('equicare.submit'), ['class' => 'btn btn-primary submit_call btn-sm']) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('equicare.close')</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/datetimepicker.js') }}" type="text/javascript"></script>
    <script type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.service_rendered_select2').select2({
                placeholder: '{{ __('equicare.select_option') }}',
                allowClear: true,
                tags: true,

            });
            $('.next_due_date').datepicker({
                format: "{{ env('date_settings') == '' ? 'yyyy-mm-dd' : env('date_settings') }}",
                todayHighlight: true,
            })
            if ( $('.service_rendered_select2').val() == 'add_new') {
                    $('.new_item').show();
            }
            $('.service_rendered_select2').on('change', function() {
                var val = $(this).val();
                if (val == 'add_new') {
                    $('.new_item').show();
                } else {
                    $('.new_item').hide();
                }
                $('.new_item').on('blur', function() {
                    var result = $('.new_item').val();
                    $.ajax({
                        url: '{{ url('call_complete_preventive_new_item') }}',
                        method: 'post',
                        data: {
                            'new_item': result
                        },
                        success: function(response) {
                            if ($(".service_rendered_select2").find("option[value='" +
                                    response.new_item_db.new_item + "']").length) {
                                $(".service_rendered_select2").val(response.new_item_db
                                    .new_item).trigger("change");
                            } else {
                                var newItem = new Option(response.new_item_db.new_item,
                                    response.new_item_db.new_item, true, true);
                                // Append it to the select
                                $(".service_rendered_select2 option:last").before(
                                    newItem).trigger('change');
                            }
                            $('.service_rendered_select2').next(".select2-container")
                                .show();
                            $('.new_item').hide();
                        }
                    });
                });
            });

            @if (count($errors->attend_call) > 0)
                $('#attend_modal').modal('show');
            @endif
            @if (count($errors->complete_call) > 0)
                $('#call_complete_modal').modal('show');
            @endif
            $('.call_complete_date_time').datetimepicker({
                format: 'Y-MM-D hh:mm A',
            });
			$('.call_attend_date_time').datetimepicker({
                format: 'Y-MM-D hh:mm A',
            });
        });
        $('.attend_btn').on('click', function() {
            var id = $(this).attr('data-id');
            $('.test').val($(this).attr('data-status'));
            $.ajax({
                url: '{{ url('admin/call/preventive_maintenance/attend') }}' + '/' + id,
                method: 'get',
                data: {
                    id: id,
                },
                success: function(response) {
                    $('.call_attend_date_time').datetimepicker({
                        format: 'Y-MM-D hh:mm A',
                    });
                    $('.call_attend_date_time').datetimepicker('destroy');
                    $('.call_attend_date_time').val(response.p_m.call_attend_date_time);

                    $('.call_attend_date_time').datetimepicker({
                        format: 'Y-MM-D hh:mm A',
                    });
                    $('.user_attended').val(response.p_m.user_attended);
                    $('.service_rendered').val(response.p_m.service_rendered);
                    $('.remarks').text(response.p_m.remarks);
                    $('.working_status').val(response.p_m.working_status);
                    $('.b_id').val(response.p_m.id);
                }
            });
        });
        $('.call_complete_btn').on('click', function() {
            var id = $(this).attr('data-id');
            $('.test').val($(this).attr('data-status'));
            $.ajax({
                url: '{{ url('admin/call/preventive_maintenance/call_complete') }}' + '/' + id,
                method: 'get',
                data: {
                    id: id,
                },
                success: function(response) {
                    $('.call_complete_date_time').datetimepicker({
                        format: 'Y-MM-D hh:mm A',
                    });
                    $('.next_due_date').datepicker({
                        todayHighlight: true,
                        format: "{{ env('date_settings') == '' ? 'yyyy-mm-dd' : env('date_settings') }}",
                    });
                    // $('.next_due_date').datepicker('destroy');
                    $('.call_complete_date_time').datetimepicker('destroy');
                    $('.call_complete_date_time').val(response.p_m.call_complete_date_time);
                    $('.call_complete_date_time').datetimepicker({
                        format: 'Y-MM-D hh:mm A',
                    });
                    $('.service_rendered_select2').val(response.p_m.service_rendered);
                    $('.next_due_date').val(response.p_m.next_due_date);
                    $('.next_due_date').datepicker({
                        format: "{{ env('date_settings') == '' ? 'yyyy-mm-dd' : env('date_settings') }}",
                        todayHighlight: true,
                    });
                    $('.service_rendered_select2').val(response.p_m.service_rendered);
                    $.each(response.n_i, function(key, value) {

                        if ($(".service_rendered_select2").find("option[value='" + value
                                .new_item + "']").length) {
                            $(".service_rendered_select2").val(value.new_item).trigger(
                            'change');
                        } else {
                            var newItem = new Option(value.new_item, value.new_item, false,
                                false);
                            $('.service_rendered_select2 option:last').before(newItem).trigger(
                                'change');
                        }
                    });
                    $('.service_rendered_select2').val(response.p_m.service_rendered);
                    $('.remarks').text(response.p_m.remarks);

                    $('.working_status').val(response.p_m.working_status);
                    $('.b_id').val(response.p_m.id);

                   $('.view_image_sign_stamp_of_incharge').attr('href',"{{ asset('uploads') }}"+'/'+response.b_m.sign_stamp_of_incharge);
                    if (response.p_m.sign_stamp_of_incharge != null) {
                        $('.view_image_sign_stamp_of_incharge').show();
                    } else {
                        $('.view_image_sign_stamp_of_incharge').hide();
                    }
                 	$('.view_image_sign_of_engineer').attr('href',"{{ asset('uploads') }}"+'/'+response.b_m.sign_of_engineer);
                    if (response.p_m.sign_of_engineer != null) {
                        $('.view_image_sign_of_engineer').show();
                    } else {
                        $('.view_image_sign_of_engineer').hide();
                    }
                }
            });
        });
    </script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}">
    <style type="text/css">
        .select2-container {
            display: block;
        }
    </style>
@endsection
