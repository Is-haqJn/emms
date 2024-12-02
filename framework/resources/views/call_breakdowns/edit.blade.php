@extends('layouts.admin')
@section('body-title')
    @lang('equicare.call_entries')
@endsection
@section('title')
    | @lang('equicare.breakdown_maintenance_call_entry')
@endsection
@section('breadcrumb')
    <li>
        <a href="{{ url('admin/call/breakdown_maintenance') }}">
            @lang('equicare.breakdown_maintenance')
        </a>
    </li>
    <li class="active">@lang('equicare.edit')</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h4 class="box-title">@lang('equicare.edit_breakdown_maintenance')</h4>
                </div>
                <div class="box-body ">
                    @include ('errors.list')
                    {!! Form::model($breakdown, ['route' => ['breakdown_maintenance.update', $breakdown->id], 'method' => 'PATCH']) !!}

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="department"> @lang('equicare.hospital') </label>

                            {!! Form::select('hospital', array_unique($hospitals) ?? [], null, [
                                'class' => 'form-control hospital_select2',
                                'placeholder' => 'Select',
                            ]) !!}
                        </div>
                        <div class="form-group col-md-4">
                            <label for="department"> @lang('equicare.department') </label>

                            {!! Form::select('department', array_unique($departments) ?? [], null, [
                                'class' => 'form-control department_select2',
                                'placeholder' => 'Select',
                            ]) !!}
                        </div>
                        <div class="form-group col-md-4">
                            <label for="name"> @lang('equicare.unique_id') </label>
                            {!! Form::select('unique_id', $unique_ids ?? [], $breakdown->equip_id ?? null, [
                                'class' => 'form-control unique_id_select2',
                                'placeholder' => 'Select Unique Id',
                            ]) !!}
                        </div>
                        <div class="form-group col-md-4">
                            <label for="equip_name"> @lang('equicare.equipment_name') </label>
                            <input type="text" name="" class="equip_name form-control"
                                value="{{ $breakdown->equipment->name ?? '' }}" disabled />
                        </div>

                        <div class="form-group col-md-4">
                            <label for="sr_no"> @lang('equicare.serial_number') </label>
                            <input type="text" name="sr_no" class="form-control sr_no"
                                value="{{ $breakdown->equipment->sr_no ?? '' }}" disabled />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="company"> @lang('equicare.company') </label>
                            <input type="text" name="company" class=" company form-control"
                                value="{{ $breakdown->equipment->company ?? '' }}" disabled />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="model"> @lang('equicare.model') </label>
                            <input type="text" name="model" class=" company form-control"
                                value="{{ $breakdown->equipment->model ?? '' }}" disabled />
                        </div>


                        <div class="form-group col-md-4">
                            <label>@lang('equicare.call_handle'):</label>
                            <div class="radio iradio">
                                <label class="login-padding">
                                    {!! Form::radio('call_handle', 'internal', null) !!} @lang('equicare.internal')
                                </label>
                                <label>
                                    {!! Form::radio('call_handle', 'external', null, ['id' => 'external']) !!} @lang('equicare.external')
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-4 report_no none-display">
                            <label for="department"> @lang('equicare.report_number') </label>
                            <input type="hidden" name="report_no" value="{{ $breakdown->report_no }}" />
                            {!! Form::text('report_no', sprintf('%04d', $breakdown->report_no), [
                                'class' => 'form-control',
                                $breakdown->call_handle == 'internal' ? 'disabled' : '',
                            ]) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="department"> @lang('equicare.call_registration_date_time') </label>
                            <div class="input-group">
                                {!! Form::text('call_register_date_time', null, ['class' => ['form-control', 'call_register_date_time']]) !!}
                                <span class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label>@lang('equicare.working_status')</label>
                            {!! Form::select(
                                'working_status',
                                [
                                    'working' => __('equicare.working'),
                                    'not working' => __('equicare.not_working'),
                                    'pending' => __('equicare.pending'),
                                ],
                                null,
                                ['placeholder' => '--select--', 'class' => 'form-control'],
                            ) !!}
                        </div>
                        <div class="form-group col-md-4">
                            <label>@lang('equicare.nature_of_problem')</label>
                            {!! Form::textarea('nature_of_problem', null, ['rows' => 2, 'class' => 'form-control']) !!}
                        </div>
                        @if ($breakdown->sign_of_engineer != null)
                            <div class="form-group col-md-4">
                                <a class="view_image_sign_of_engineer"
                                    href="{{ asset('/uploads/sign_of_enginner/' . $breakdown->sign_of_engineer) }}"
                                    target="_blank">
                                    View Sign Of Engineer
                                </a>
                            </div>
                        @endif
                        @if (isset($breakdown->sign_stamp_of_incharge))
                            <div class="form-group col-md-4">

                                <a class="view_image_sign_stamp_of_incharge"
                                    href="{{ asset('uploads/sign_stamp_of_incharge/' . $breakdown->sign_stamp_of_incharge) }}"
                                    target="_blank">
                                    View Sign Of In Charge
                                </a>
                            </div>
                        @endif
                        <div class="form-group col-md-4">
                            <div class="checkbox icheck">
                                <label>
                                    {!! Form::checkbox('is_contamination', 1, null) !!}
                                    @lang('equicare.is_contamination')</label>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <input type="hidden" name="equip_id" id="equip_id" value="" />
                            <input type="submit" value="@lang('equicare.submit')" class="btn btn-primary btn-flat">
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/datetimepicker.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // fetch  equipment data
            $('.unique_id_select2').trigger('change');
            $('.unique_id_select2').select2({
                placeholder: '{{ __('equicare.select_option') }}',
                allowClear: true
            });
            $('.hospital_select2').select2({
                placeholder: '{{ __('equicare.select_option') }}',
                allowClear: true
            });
            $('.department_select2').select2({
                placeholder: '{{ __('equicare.select_option') }}',
                allowClear: true
            });
            if ($('#external').attr('checked') == 'checked') {
                $('.report_no').css('display', 'block');
            }
            $('#external').on('ifChecked ifUnchecked', function(e) {
                if (e.type == 'ifChecked') {
                    $('.report_no').show();
                } else {
                    $('.report_no').hide();
                }
            })
            $('.call_register_date_time').datetimepicker({
                format: 'Y-MM-D hh:mm A',
                sideBySide: true,
            })
        });
        $('#equip_id').val({{ $breakdown->equip_id }});

        $('.unique_id_select2').on('change', function() {
            var value = $(this).val();
            $('#equip_id').val(value);
            var equip_name = $('.equip_name');
            var hospitals = $('.hospital_select2');
            var sr_no = $('.sr_no');
            var company = $('.company');
            var model = $('.model');
            var department = $('.department_select2');
            if (value == "") {
                equip_name.val("");
                sr_no.val("");
                company.val("");
                model.val("");
                department.val("");
            }
            if (value != "") {
                $.ajax({
                    url: "{{ url('unique_id_breakdown') }}",
                    type: 'get',

                    data: {
                        'id': value
                    },
                    success: function(data) {
                        equip_name.val(data.success.name);
                        hospitals.val(data.success.hospital_id);
                        sr_no.val(data.success.sr_no);
                        company.val(data.success.company);
                        model.val(data.success.model);
                        department.val(data.success.department);

                        new PNotify({
                            title: ' Success!',
                            text: "{{ __('equicare.equipment_data_fetched') }}",
                            type: 'success',
                            delay: 3000,
                            nonblock: {
                                nonblock: true
                            }
                        });

                        $('.unique_id_select2').select2({
                            placeholder: '{{ __('equicare.select_option') }}',
                            allowClear: true
                        });
                        $('.hospital_select2').select2({
                            placeholder: '{{ __('equicare.select_option') }}',
                            allowClear: true
                        });
                        $('.department_select2').select2({
                            placeholder: '{{ __('equicare.select_option') }}',
                            allowClear: true
                        });

                    }
                });
            }
        });

        $('.hospital_select2').on('change', function() {
            var value = $(this).val();
            var equip_name = $('.equip_name');
            var hospitals = $('.hospital_select2');
            var department = $('.department_select2');
            var unique_id = $('.unique_id_select2');
            var sr_no = $('.sr_no');
            var company = $('.company');
            var model = $('.model');
            if (value == "") {
                equip_name.val("");
                sr_no.val("");
                company.val("");
                model.val("");
                department.val("");
                unique_id.trigger("change");
                unique_id.val("");

            }
            if (value != "") {
                $.ajax({
                    url: "{{ url('hospital_breakdown') }}",
                    type: 'get',

                    data: {
                        'id': value
                    },
                    success: function(data) {
                        console.log(data);
                        department.empty();
                        unique_id.empty();
                        if (data.department) {
                            department.append(
                                '<option value=""></option>'
                            );
                            $.each(data.department, function(k, v) {
                                department.append(
                                    '<option value="' + k + '">' + v + '</option>'
                                );
                            });
                        }

                        if (data.unique_id) {
                            unique_id.append(
                                '<option value=""></option>'
                            );
                            $.each(data.unique_id, function(k, v) {
                                unique_id.append(
                                    '<option value="' + k + '">' + v + '</option>'
                                );
                            });
                        }

                        $('.unique_id_select2').select2({
                            placeholder: '{{ __('equicare.select_option') }}',
                            allowClear: true
                        });
                        $('.hospital_select2').select2({
                            placeholder: '{{ __('equicare.select_option') }}',
                            allowClear: true
                        });
                        $('.department_select2').select2({
                            placeholder: '{{ __('equicare.select_option') }}',
                            allowClear: true
                        });

                    }
                });
            }
        });


        $('.department_select2').on('change', function() {
            var value = $(this).val();
            var equip_name = $('.equip_name');
            var hospitals = $('.hospital_select2');

            var unique_id = $('.unique_id_select2');
            var sr_no = $('.sr_no');
            var company = $('.company');
            var model = $('.model');
            if (value == "") {
                equip_name.val("");
                sr_no.val("");
                company.val("");
                model.val("");
                $(this).val("");
                unique_id.trigger("change");
                unique_id.val("");

            }
            if (value != "") {
                $.ajax({
                    url: "{{ url('department_breakdown') }}",
                    type: 'get',

                    data: {
                        'department': value,
                        'hospital_id': hospitals.val()
                    },
                    success: function(data) {
                        unique_id.empty();

                        if (data.unique_id) {
                            unique_id.append(
                                '<option value=""></option>'
                            );
                            $.each(data.unique_id, function(k, v) {
                                unique_id.append(
                                    '<option value="' + k + '">' + v + '</option>'
                                );
                            });
                        }
                        $('.unique_id_select2').select2({
                            placeholder: '{{ __('equicare.select_option') }}',
                            allowClear: true
                        });
                        $('.hospital_select2').select2({
                            placeholder: '{{ __('equicare.select_option') }}',
                            allowClear: true
                        });
                        $('.department_select2').select2({
                            placeholder: '{{ __('equicare.select_option') }}',
                            allowClear: true
                        });

                    }
                });
            }
        });
    </script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'csrftoken': '{{ csrf_token() }}'
            }
        });
    </script>
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}">
@endsection
