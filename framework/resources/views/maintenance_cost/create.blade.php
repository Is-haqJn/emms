@extends('layouts.admin')
@section('body-title')
    @lang('equicare.maintenance_cost')
@endsection
@section('title')
    | @lang('equicare.maintenance_cost_create')
@endsection
@section('breadcrumb')
    <li>
        <a href="{{ url('admin/maintenance_cost') }}">
            @lang('equicare.maintenance_cost')
        </a>
    </li>
    <li class="active">@lang('equicare.create')</li>
@endsection
@section('content')
    <div class="row">
        {!! Form::open([
            'route' => 'maintenance_cost.store',
            'class' => 'form',
            'method' => 'POST',
        ]) !!}
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">@lang('equicare.maintenance_cost_create')</h4>
                </div>
                <div class="box-body ">
                    {{-- <form class="form" method="post" action="{{ route('maintenance_cost.store') }}"> --}}
                    {{ csrf_field() }}
                    {{ method_field('POST') }}
                    <div class="row">
                        <div class="form-group col-md-4">
                            {!! Form::label('hospital_id', __('equicare.hospital')) !!}
                            {!! Form::select('hospital_id', $hospitals ?? [], null, [
                                'class' => $errors->has('hospital_id')
                                    ? 'is-invalid form-control select2_hospital'
                                    : 'form-control select2_hospital',
                                'placeholder' => 'Select Hospital',
                            ]) !!}
                            @if ($errors->has('hospital_id'))
                                <strong class="invalid-feedback">
                                    <span>{{ $errors->first('hospital_id') }}
                                    </span>
                                </strong>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('equicare.type'):</label>
                            <div class="radio iradio">
                                <label class="login-padding">
                                    {!! Form::radio('type', 'amc', false, ['class' => $errors->has('type') ? 'is-invalid' : '','checked']) !!} @lang('equicare.annual_cost')
                                </label>
                                <label>
                                    {!! Form::radio('type', 'cmc', false, ['class' => $errors->has('type') ? 'is-invalid' : '']) !!} @lang('equicare.comprehensive_cost')
                                </label>
                                <br />
                                @if ($errors->has('type'))
                                    <strong class="invalid-feedback">
                                        <span>{{ $errors->first('type') }}
                                        </span>
                                    </strong>
                                @endif
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label css="margintpop">@lang('equicare.cost_by'):</label>
                            <div class="radio iradio zmargin">
                                <label class="login-padding">
                                    {!! Form::radio('cost_by', 'us',null,['checked']) !!} @lang('equicare.own_company')
                                </label>
                                <label>
                                    {!! Form::radio('cost_by', 'tp', null, ['id' => 'tp']) !!} @lang('equicare.third_party')
                                </label>
                                @if ($errors->has('cost_by'))
                                    <strong class="invalid-feedback">
                                        <span>{{ $errors->first('cost_by') }}
                                        </span>
                                    </strong>
                                @endif
                            </div>
                        </div>
                        <div class="form-group col-md-9 tp_details none-display">
                            <div class="row no-gutters">
                                <div class="form-group col-md-4">
                                    <label for="tp_name"> @lang('equicare.name') </label>
                                    <input type="text" id="tp_name" name="tp_name"
                                        class="{{ $errors->has('tp_name') ? 'is-invalid ' : '' }}form-control"
                                        value="{{ old('tp_name') }}" />
                                    @if ($errors->has('tp_name'))
                                        <strong class="invalid-feedback">
                                            <span>{{ $errors->first('tp_name') }}
                                            </span>
                                        </strong>
                                    @endif
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tp_mobile"> @lang('equicare.mobile') </label>
                                    <input type="text" id="tp_mobile" name="tp_mobile"
                                        class="{{ $errors->has('tp_mobile') ? 'is-invalid ' : '' }}form-control"
                                        value="{{ old('tp_mobile') }}" />
                                    @if ($errors->has('tp_mobile'))
                                        <strong class="invalid-feedback">
                                            <span>{{ $errors->first('tp_mobile') }}
                                            </span>
                                        </strong>
                                    @endif
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tp_email"> @lang('equicare.email') </label>
                                    <input type="email" id="tp_email" name="tp_email"
                                        class="{{ $errors->has('tp_email') ? 'is-invalid ' : '' }}form-control"
                                        value="{{ old('tp_email') }}" />
                                    @if ($errors->has('tp_email'))
                                        <strong class="invalid-feedback">
                                            <span>{{ $errors->first('tp_email') }}
                                            </span>
                                        </strong>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::label('equipments', __('equicare.select_equipments')) !!}
                    <div class="add_row_equipments">
                        @php($i = 0)
                        @if (old('cost'))
                            @foreach (old('cost') as $input)
                                <div class="row no-gutters">
                                    <div class="form-group col-md-3">
                                        {!! Form::select('equipments[]',[], old('equipments.' . $i), [
                                            'class' => $errors->has('equipments.' . $i)
                                                ? 'is-invalid form-control select2_equipments'
                                                : 'form-control select2_equipments',
                                            'id' => 'equipments' . ($i = 1),
                                            'required'=>'required',
                                        ]) !!}
                                        @if ($errors->has('equipments.' . $i))
                                            <strong class="invalid-feedback">
                                                <span>{{ $errors->first('equipments.' . $i) }}
                                                </span>
                                            </strong>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-3">
                                        {!! Form::text('start_dates[]', old('start_dates.' . $i), [
                                            'class' => $errors->has('start_dates.' . $i) ? 'is-invalid start_dates form-control' : 'form-control start_dates',
                                            'placeholder' => __('equicare.enter_start_date'),
                                            'autocomplete' => 'off',
                                            'required'=>'required',
                                        ]) !!}
                                        @if ($errors->has('start_dates.' . $i))
                                            <strong class="invalid-feedback">
                                                <span>{{ $errors->first('start_dates.' . $i) }}
                                                </span>
                                            </strong>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-3">
                                        {!! Form::text('end_dates[]', old('end_dates.' . $i), [
                                            'class' => $errors->has('end_dates.' . $i) ? 'is-invalid end_dates form-control' : 'form-control end_dates',
                                            'placeholder' => __('equicare.enter_end_date'),
                                            'autocomplete' => 'off',
                                            'required'=>'required',
                                        ]) !!}
                                        @if ($errors->has('end_dates.' . $i))
                                            <strong class="invalid-feedback">
                                                <span>{{ $errors->first('end_dates.' . $i) }}
                                                </span>
                                            </strong>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-3">
                                        {!! Form::number('cost[]', old('cost.' . $i), [
                                            'class' => $errors->has('cost.' . $i) ? 'is-invalid cost form-control' : 'form-control cost',
                                            'placeholder' => __('equicare.enter_cost'),
                                            'autocomplete' => 'off',
                                            'required'=>'required',
                                        ]) !!}
                                        @if ($errors->has('cost.' . $i))
                                            <strong class="invalid-feedback">
                                                <span>{{ $errors->first('cost.' . $i) }}
                                                </span>
                                            </strong>
                                        @endif
                                    </div>
                                    @php($i++)
                                </div>
                            @endforeach
                        @else
                            <div class="row no-gutters">
                                <div class="form-group col-md-3">
                                    {!! Form::select('equipments[]', [], null, [
                                        'class' => 'form-control select2_equipments',
                                        'id' => 'equipments1',
                                        'required'=>'required',
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-3">
                                    {!! Form::text('start_dates[]', null, [
                                        'class' => 'form-control start_dates',
                                        'placeholder' => __('equicare.enter_start_date'),
                                        'id' => 'start_dates1',
                                        'autocomplete' => 'off',
                                        'required'=>'required',
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-3">
                                    {!! Form::text('end_dates[]', null, [
                                        'class' => 'form-control end_dates',
                                        'placeholder' => __('equicare.enter_end_date'),
                                        'id' => 'end_dates1',
                                        'autocomplete' => 'off',
                                        'required'=>'required',
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-3">
                                    {!! Form::number('cost[]', null, ['class' => 'form-control', 'placeholder' => __('equicare.enter_cost'),'required'=>'required']) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-12" id="fa-fa-plus-id">
                        <button type="button" class="pull-right btn btn-primary btn-sm add_btn btn-flat leftmargin"><i
                                class="fa fa-plus" id="fa-fa-plus-id"></i> @lang('equicare.add_more_equipments')</button>
                        <button type="button"
                            class="pull-right btn btn-danger btn-sm delete_row_btn btn-flat none-display"><i
                                class="fa fa-close"></i> @lang('equicare.delete_line')</button>
                    </div>
                    <div class="form-group col-md-12 login-padding">
                        {!! Form::submit(__('equicare.submit'), ['class' => 'btn btn-primary btn-flat']) !!}
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')
    
    <script src="{{ asset('assets/js/datetimepicker.js') }}" type="text/javascript"></script>
    <script type="text/javascript">

    function equipment_old_select(){
    
        var oldEquipments = {!! json_encode(old('equipments')) !!};
        console.log(oldEquipments,'otuside');

        $('.select2_equipments').each(function(index, element) {
        console.log('inside');
            // Get the corresponding old value from the array
            var oldValue = oldEquipments[index];
            // Set the value of the select dropdown to the old value
            $(element).val(oldValue);
            // Fire the change event for the select dropdown
            $(element).trigger('change');
        });
}
        $(document).ready(function() {

            
            @if ($errors->has('tp_name') || $errors->has('tp_email') || $errors->has('tp_mobile'))
                $('div.tp_details').show();
            @endif
            if ($('#tp').attr('checked') == 'checked') {
                $('.tp_details').css('display', 'block');
            }
            $('#tp').on('ifChecked ifUnchecked', function(e) {
                if (e.type == 'ifChecked') {
                    $('.tp_details').show();
                } else {
                    $('.tp_details').hide();
                }
            });
            var dateFormat = "{{env('date_settings')=='' ? 'yyyy-mm-dd' : env('date_settings')}}";

            $('.start_dates').datepicker({
                format:"{{env('date_settings')=='' ? 'yyyy-mm-dd' : env('date_settings')}}",
	todayHighlight: true,
	autoclose: true,
	onSelect: function(selected) {
		var minDate = $(this).datepicker('getDate');
		minDate.setDate(minDate.getDate() + 1); // Set minDate for due_date to be one day after date_of_calibration
		$('.end_dates').datepicker('setStartDate', minDate);
	}
});
            $('.end_dates').datepicker({
                format:"{{env('date_settings')=='' ? 'yyyy-mm-dd' : env('date_settings')}}",
	todayHighlight: true,
	autoclose: true,
	onSelect: function(selected) {
		var maxDate = $(this).datepicker('getDate');
		maxDate.setDate(maxDate.getDate() - 1); 
		$('.start_dates').datepicker('setEndDate', maxDate);
	}
});

$('.end_dates').on('change', function() {
	console.log("fsdfiedjf");
	var calibrationDate = $('.start_dates').datepicker('getDate');
	var dueDate = $(this).datepicker('getDate');

	if (dueDate && calibrationDate && dueDate <= calibrationDate) {
		$(this).val(''); 
		alert("End date cannot be earlier than or the same as the start date.");
	}
});
            $('.select2_equipments').select2({
                placeholder: '{{ __('equicare.select_option') }}',
                allowClear: true
            });
            $('.select2_hospital').select2({
                placeholder: 'Select an option',
                allowClear: true
            });
            var $i = 1;
            $('.add_btn').on('click', function(e) {
                $i++;
                if ($('.add_row_equipments').children('.row').length >= 1) {
                    $('.delete_row_btn').show();
                } else {
                    $('.delete_row_btn').hide();
                }
                $(this).parent().siblings('.add_row_equipments').append(
                    '<div class="row no-gutters">' +
                    '<div class="form-group col-md-3">  ' +
                    '	<select name="equipments[]" class="form-control select2_equipments" id="equipments' +
                    $i + '" autocomplete="off" required>' +
                    '		<option value=""> </option>' +
                    '	</select>' +
                    '</div>  ' +
                    '<div class="form-group col-md-3">  ' +
                    ' <input type="text" name="start_dates[]" id="start_dates' + $i +
                    '" class="form-control start_dates" placeholder="{{ __('equicare.enter_start_date') }}" autocomplete="off" required>	' +
                    '</div>  ' +
                    '<div class="form-group col-md-3">  ' +
                    ' <input type="text" name="end_dates[]" id="end_dates' + $i +
                    '" class="form-control end_dates" placeholder="{{ __('equicare.enter_end_date') }}" autocomplete="off" required>	' +
                    ' </div>  ' +
                    ' <div class="form-group col-md-3">  ' +
                    ' 	{!! Form::number('cost[]', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Cost',
                        'autocomplete' => 'off',
                        'required'=>'required'
                    ]) !!}  ' +
                    ' </div>  ' +
                    ' </div> ');
                $('#equipments' + $i).select2({
                    placeholder: '{{ __('equicare.select_option') }}',
                    allowClear: true,
                });
                $('#start_dates' + $i).datepicker({
                    todayHighlight: true,
                    format:"{{env('date_settings')=='' ? 'yyyy-mm-dd' : env('date_settings')}}",
                });
                $('#end_dates' + $i).datepicker({
                    todayHighlight: true,
                    format:"{{env('date_settings')=='' ? 'yyyy-mm-dd' : env('date_settings')}}",
                });
            });

            if ($('.add_row_equipments').children('.row').length > 1) {
                $('.delete_row_btn').show();
            }
            $('.delete_row_btn').on('click', function(e) {

                if ($('.add_row_equipments').children('.row').length == 2) {
                    $('.delete_row_btn').hide();
                } else {
                    $('.delete_row_btn').show();
                }
                e.preventDefault();
                $(this).parent().siblings('.add_row_equipments').children('.row :last').remove();
            });
            setTimeout(loadEquipAjax, 500);
            $('select[name=hospital_id]').on('change', function() {
                var hospital_id = $(this).val();
                $.ajax({
                    url: "{{ url('get_equipment') }}",
                    type: 'get',
                    data: {
                        'hospital_id': hospital_id,
                    },
                    success: function(data) {
                        if (data.equipments.length == 0) {
                            alert('{{ __('equicare.select_other_hospital') }}');
                        } else {
                            $('.select2_equipments').empty();
                            $('.select2_equipments').append(
                                '<option value=""></option>');
                            for (n in data.equipments) {
                                $('.select2_equipments').select2('destroy');
                                $('.select2_equipments').append(
                                    '<option value=' + n + '>' + data.equipments[n] +
                                    '</option>'
                                );
                                $('.select2_equipments').select2({
                                    placeholder: '{{ __('equicare.select_option') }}',
                                    allowClear: true,
                                    
                                
                                });
                            }
                        }
                        if ({!! json_encode(old('equipments')) !!} !== null) {
                            equipment_old_select();
                        }
                        // Get the array of old equipment values
                    },
                    error: function(data) {

                    }
                });
            });
		
		
					$('#fa-fa-plus-id').click(function() { 
						console.log('hi');
						var hospital_id = $('select[name=hospital_id]').val();
						console.log(hospital_id);
					$.ajax({
						url: "{{ url('get_equipment') }}",
						type: 'get',
						data: {
							'hospital_id': hospital_id,
						},
						success: function(data) {	
							
							for (n in data.equipments) {
                                $('.select2_equipments').select2('destroy');
                                $('.select2_equipments').append(
                                    '<option value=' + n + '>' + data.equipments[n] +
                                    '</option>'
                                );
                                $('.select2_equipments').select2({
                                    placeholder: '{{ __('equicare.select_option') }}',
                                    allowClear: true,
									
                                
                                });
                            }
						// 		$('.select2_equipments').append(
						// 			'<option value=""></option>');
						// 		for (n in data.equipments) {
						// 			$('.select2_equipments').select2('destroy');
						// 			$('.select2_equipments').append(
						// 				'<option value=' + n + '>' + data.equipments[n] +
						// 				'</option>'
						// 			);
									
						// 		}
							
						// },
						},
						error: function(data) {

						}
					});
				
					
			});
        });

        function loadEquipAjax() {
            if ($('select[name=hospital_id]').val()) {
                $('select[name=hospital_id]').trigger('change');
            }
        }
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
    <style type="text/css">
        .no-gutters {
            margin-right: 0;
            margin-left: 0;
        }

        .no-gutters>.col-md-3,
        .no-gutters>.col-md-4 {
            padding-right: 10;
            padding-left: 0;
        }

        .is-invalid+span.select2-container {
            border: 1px solid red !important;
        }

        .is-invalid {
            border: 1px solid red !important;
        }

        .invalid-feedback {
            color: red;
        }

        .is-invalid < .iradio_minimal-blue {
            background: url(red.png) no-repeat;
        }

        .form-group {
            max-height: 62px;
        }

        .tp_details .row {
            display: block;
        }
    </style>
@endsection
