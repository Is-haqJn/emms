@extends('layouts.admin')
@section('body-title')
@lang('equicare.calibrations')
@endsection
@section('title')
| @lang('equicare.calibrations')
@endsection
@section('breadcrumb')
<li>
	<a href="{{ url('admin/calibration') }}">
		@lang('equicare.calibrations')
	</a>
</li>
<li class="active">@lang('equicare.create')</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">@lang('equicare.create_calibration')</h4>
			</div>
			<div class="box-body">
				@include('errors.list')
				{!! Form::open(['route'=>'calibration.store', 'method'=>'POST','files'=>true]) !!}
				<div class="row">
					<div class="form-group col-md-4">
						<label for="department"> @lang('equicare.hospital') </label>

						{!! Form::select('hospital',array_unique($hospitals)??[],null,['class'=>'form-control hospital_select2','placeholder'=>'Select']) !!}
					</div>
					<div class="form-group col-md-4">
						<label for="department"> @lang('equicare.department') </label>

						{!! Form::select('department',array_unique($departments)??[],null,['class'=>'form-control department_select2','placeholder'=>'Select']) !!}
					</div>
					<div class="form-group col-md-4">
						<label for="unique_id"> @lang('equicare.unique_id') </label>
						{!! Form::select('unique_id',$unique_ids??[],null,['class'=>'form-control unique_id_select2','placeholder'=>'Select Unique Id']) !!}
					</div>
					<div class="form-group col-md-4">
						<label for="equip_name"> @lang('equicare.equipment_name') </label>
						<input type="text" name="" class="equip_name form-control" value="" disabled/>
						{!! Form::hidden('equip_id',null,['id'=>'equip_id']) !!}
					</div>

					<div class="form-group col-md-4">
						<label for="sr_no"> @lang('equicare.serial_number') </label>
						<input type="text" name="sr_no" class="form-control sr_no" value="" disabled/>
					</div>
					<div class="form-group col-md-4">
						<label for="company"> @lang('equicare.company') </label>
						<input type="text" name="company" class=" company form-control" value="" disabled/>
					</div>
					<div class="form-group col-md-4">
						<label for="model"> @lang('equicare.model') </label>
						<input type="text" name="model" class=" model form-control" value="" disabled/>
					</div>
					<div class="form-group col-md-4">
						<label for="model"> @lang('equicare.date_pm') </label>
						<input type="text" name="date_pm" class=" date_pm form-control" value="" disabled/>
					</div>
					<div class="form-group col-md-4">
						<label for="model"> @lang('equicare.due_pm') </label>
						<input type="text" name="due_pm" class=" due_pm form-control" value="" disabled/>
					</div>
					<div class="form-group col-md-4">
						{!! Form::label('date_of_calibration',__('equicare.calibration_date')) !!}
						{!! Form::text('date_of_calibration',null,['class' => 'date_of_calibration form-control']) !!}
					</div>
					<div class="form-group col-md-4">
						{!! Form::label('due_date',__('equicare.due_date')) !!}
						{!! Form::text('due_date',null,['class' => 'due_date form-control']) !!}
					</div>
					<div id="dueError" style="color:red;"></div>
					<div class="form-group col-md-4">
						{!! Form::label('certificate_no',__('equicare.certificate_no')) !!}
						{!! Form::text('certificate_no',null,['class' => 'form-control']) !!}
					</div>
					<div class="form-group col-md-4">
						{!! Form::label('company_this',__('equicare.company')) !!}
						{!! Form::text('company_this',null,['class' => 'form-control']) !!}
					</div>
					<div class="form-group col-md-4">
						{!! Form::label('contact_person',__('equicare.contact_person')) !!}
						{!! Form::text('contact_person',null,['class' => 'form-control']) !!}
					</div>
					<div class="form-group col-md-4">
						{!! Form::label('contact_person_no',__('equicare.contact_person_no')) !!}
						{!! Form::text('contact_person_no',null,['class' => 'form-control phone']) !!}
					</div>
					<div class="form-group col-md-4">
						{!! Form::label('engineer_no',__('equicare.engineer_no')) !!}
						{!! Form::text('engineer_no',null,['class' => 'form-control phone']) !!}
					</div>
					<div class="form-group col-md-4">
						{!! Form::label('traceability_certificate_no',__('equicare.traceability_certificate_no')) !!}
						{!! Form::text('traceability_certificate_no',null,['class' => 'form-control']) !!}
					</div>
					<div class="form-group col-md-4">
						{!! Form::label('calibration_certificate',__('equicare.calibration_certificate')) !!}
						{!! Form::file('calibration_certificate',null,['class' => 'form-control','accept' => '.jpg, .png, .jpeg, .pdf, .docs']) !!}
					</div>
					<div class="form-group col-md-12">
						{!! Form::submit(__('equicare.submit'),['class' => 'btn btn-primary btn-flat']) !!}
					</div>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
	$(function(){
		$('.unique_id_select2').select2({
			placeholder: '{{__("equicare.select_option")}}',
			allowClear: true
		});
		 $('.hospital_select2').select2({
	     	placeholder: '{{__("equicare.select_option")}}',
	     	allowClear: true
	     });
	     $('.department_select2').select2({
	     	placeholder: '{{__("equicare.select_option")}}',
	     	allowClear: true
	     });
           
		 var dateFormat = "{{env('date_settings')=='' ? 'yyyy-mm-dd' : env('date_settings')}}";

$('.date_of_calibration').datepicker({
	format:"{{env('date_settings')=='' ? 'yyyy-mm-dd' : env('date_settings')}}",
	todayHighlight: true,
	autoclose: true,
	onSelect: function(selected) {
		var minDate = $(this).datepicker('getDate');
		minDate.setDate(minDate.getDate() + 1); // Set minDate for due_date to be one day after date_of_calibration
		$('.due_date').datepicker('setStartDate', minDate);
	}
});

$('.due_date').datepicker({
	format:"{{env('date_settings')=='' ? 'yyyy-mm-dd' : env('date_settings')}}",
	todayHighlight: true,
	autoclose: true,
	onSelect: function(selected) {
		var maxDate = $(this).datepicker('getDate');
		maxDate.setDate(maxDate.getDate() - 1); 
		$('.date_of_calibration').datepicker('setEndDate', maxDate);
	}
});

$('.due_date').on('change', function() {
	console.log("fsdfiedjf");
	var calibrationDate = $('.date_of_calibration').datepicker('getDate');
	var dueDate = $(this).datepicker('getDate');

	if (dueDate && calibrationDate && dueDate <= calibrationDate) {
		$(this).val(''); // Clear the input
		alert("Due date cannot be earlier than or the same as the calibration date.");
		// $("#dueError").text('Due date cannot be earlier than or the same as the calibration date.')
	}else{
		// $('#dueError').text('');
	}
});
	});
	$('.unique_id_select2').on('change',function(){
		var value = $(this).val();
		$('#equip_id').val(value);
		var equip_name = $('.equip_name');
		var hospitals = $('.hospital_select2');
		var sr_no = $('.sr_no');
		var company = $('.company');
		var model = $('.model');
		var department = $('.department_select2');
		var date_pm = $('.date_pm');
		var due_pm = $('.due_pm');
		if(value==""){
			equip_name.val("");
			sr_no.val("");
			company.val("");
			model.val("");
			department.val("");
			date_pm.val("");
			due_pm.val("");
		}
		if(value !=""){
			$.ajax({
				url : "{{ url('unique_id_breakdown')}}" ,
				type : 'get',

				data:{'id' : value },
				success:function(data){
			    		equip_name.val(data.success.name);
			    		hospitals.val(data.success.hospital_id);
			    		sr_no.val(data.success.sr_no);
			    		company.val(data.success.company);
			    		model.val(data.success.model);
			    		department.val(data.success.department);
			    		date_pm.val(data.success.date_pm);
			    		due_pm.val(data.success.due_pm);

			    		new PNotify({
			    			title: ' Success!',
			    			text: "{{__('equicare.equipment_data_fetched')}}",
			    			type: 'success',
			    			delay: 3000,
			    			nonblock: {
			    				nonblock: true
			    			}
			    		});

			    		$('.unique_id_select2').select2({
			    			placeholder: '{{__("equicare.select_option")}}',
			    			allowClear: true
			    		});
			    		$('.hospital_select2').select2({
			    			placeholder: '{{__("equicare.select_option")}}',
			    			allowClear: true
			    		});
			    		$('.department_select2').select2({
			    			placeholder: '{{__("equicare.select_option")}}',
			    			allowClear: true
			    		});

			    	}
			    });
		}
	});

	$('.hospital_select2').on('change',function(){
		var value = $(this).val();
				var equip_name = $('.equip_name');
				var hospitals = $('.hospital_select2');
				var department = $('.department_select2');
				var unique_id = $('.unique_id_select2');
				var sr_no = $('.sr_no');
				var company = $('.company');
				var model = $('.model');
				var date_pm = $('.date_pm');
				var due_pm = $('.due_pm');
				if(value==""){
					equip_name.val("");
					sr_no.val("");
					company.val("");
					model.val("");
					department.val("");
					unique_id.trigger("change");
					unique_id.val("");
					date_pm.val("");
					due_pm.val("");
				}
				if(value !=""){
					$.ajax({
						url : "{{ url('hospital_breakdown')}}" ,
						type : 'get',

						data:{'id' : value },
						success:function(data){
							console.log(data);
							department.empty();
							unique_id.empty();
			    		if (data.department) {
			    			department.append(
			    				'<option value=""></option>'
			    				);
			    			$.each(data.department,function(k,v){
			    				department.append(
			    					'<option value="'+k+'">'+v+'</option>'
			    					);
			    			});
			    		}

			    		if (data.unique_id) {
			    			unique_id.append(
			    				'<option value=""></option>'
			    				);
			    			$.each(data.unique_id,function(k,v){
			    				unique_id.append(
			    					'<option value="'+k+'">'+v+'</option>'
			    					);
			    			});
			    		}
				     $('.unique_id_select2').select2({
				     	placeholder: '{{__("equicare.select_option")}}',
				     	allowClear: true
				     });
				     $('.hospital_select2').select2({
				     	placeholder: '{{__("equicare.select_option")}}',
				     	allowClear: true
				     });
				     $('.department_select2').select2({
				     	placeholder: '{{__("equicare.select_option")}}',
				     	allowClear: true
				     });

				 }
				});
				}
			});


	$('.department_select2').on('change',function(){
		var value = $(this).val();
				var equip_name = $('.equip_name');
				var hospitals = $('.hospital_select2');
				var unique_id = $('.unique_id_select2');
				var sr_no = $('.sr_no');
				var company = $('.company');
				var model = $('.model');
				var date_pm = $('.date_pm');
				var due_pm = $('.due_pm');
				if(value==""){
					equip_name.val("");
					sr_no.val("");
					company.val("");
					model.val("");
					$(this).val("");
					unique_id.trigger("change");
					unique_id.val("");
					date_pm.val("");
					due_pm.val("");
				}
				if(value !=""){
					$.ajax({
						url : "{{ url('department_breakdown')}}" ,
						type : 'get',

						data:{
							'department' : value,
							'hospital_id':hospitals.val()
						},
						success:function(data){
			    		 unique_id.empty();
			    		if (data.unique_id) {
			    			unique_id.append(
			    				'<option value=""></option>'
			    				);
			    			$.each(data.unique_id,function(k,v){
			    				unique_id.append(
			    					'<option value="'+k+'">'+v+'</option>'
			    					);
			    			});
			    		}
				     $('.unique_id_select2').select2({
				     	placeholder: '{{__("equicare.select_option")}}',
				     	allowClear: true
				     });
				     $('.hospital_select2').select2({
				     	placeholder: '{{__("equicare.select_option")}}',
				     	allowClear: true
				     });
				     $('.department_select2').select2({
				     	placeholder: '{{__("equicare.select_option")}}',
				     	allowClear: true
				     });

				 }
				});
				}
			});
</script>
<script type="text/javascript">
	$.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
</script>
@endsection