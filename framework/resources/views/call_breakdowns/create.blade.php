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
<li class="active">@lang('equicare.create')</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">@lang('equicare.create_breakdown_maintenance')</h4>
			</div>
			<div class="box-body ">
				@include ('errors.list')
				<div class="row">

					<div class="form-group col-md-4">
						<label for="department"> @lang('equicare.hospital') </label>

						{!! Form::select('hospital',$hospitals??[],null,['class'=>'form-control
						hospital_select2','placeholder'=>'Select']) !!}
					</div>
					<div class="form-group col-md-4">
						<label for="department"> @lang('equicare.department') </label>

						{!! Form::select('department',array_unique($departments)??[],null,['class'=>'form-control
						department_select2','placeholder'=>'Select']) !!}
					</div>
					<div class="form-group col-md-4">
						<label for="unique_id"> @lang('equicare.unique_id') </label>
						{!! Form::select('unique_id',$unique_ids??[],null,['class'=>'form-control
						unique_id_select2','placeholder'=>'Select Unique Id']) !!}
					</div>
					<div class="form-group col-md-4">
						<label for="equip_name"> @lang('equicare.equipment_name') </label>
						<input type="text" name="" class="equip_name form-control" value="" disabled />
					</div>

					<div class="form-group col-md-4">
						<label for="sr_no"> @lang('equicare.serial_number') </label>
						<input type="text" name="sr_no" class="form-control sr_no" value="" disabled />
					</div>
					<div class="form-group col-md-4">
						<label for="company"> @lang('equicare.company') </label>
						<input type="text" name="company" class=" company form-control" value="" disabled />
					</div>
					<div class="form-group col-md-4">
						<label for="model"> @lang('equicare.model') </label>
						<input type="text" name="model" class=" company form-control" value="" disabled />
					</div>

				</div>
			</div>
			<div class="box-body">
				<form class="form" method="post" action="{{ route('breakdown_maintenance.store') }}">
					{{ csrf_field() }}
					{{ method_field('POST') }}
					<div class="row">
						<div class="form-group col-md-4">
							<label>@lang('equicare.call_handle'):</label>
							<div class="radio iradio">
								<label class="login-padding">
									{!! Form::radio('call_handle', 'internal')!!} @lang('equicare.internal')
								</label>
								<label>
									{!! Form::radio('call_handle', 'external',null,['id'=>'external'])!!} @lang('equicare.external')
								</label>
							</div>
						</div>
						<div class="form-group col-md-4 report_no none-display">
							<label for="department"> @lang('equicare.report_number')  </label>
							<input type="text" name="report_no" class="form-control" value="" />
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-4">
							<label for="department"> @lang('equicare.call_registration_date_time') </label>
							<div class="input-group">
								<input type="text" name="call_register_date_time" class="form-control call_register_date_time"
									value="" />
								<span class="input-group-addon">
									<i class="fa fa-clock-o"></i>
								</span>
							</div>
						</div>
						<div class="form-group col-md-4">
							<label>@lang('equicare.working_status')</label>
							{!! Form::select('working_status',[
							'working' => __("equicare.working"),
							'not working' => __("equicare.not_working"),
							'pending' => __("equicare.pending")
							],null,['placeholder' => '--select--','class' => 'form-control']) !!}
						</div>
						<div class="form-group col-md-4">
							<label>@lang('equicare.nature_of_problem')</label>
							{!! Form::textarea('nature_of_problem',null,['rows' => 2, 'class' => 'form-control']) !!}
						</div>
						<div class="form-group col-md-4">
							<div class="checkbox icheck">
								<label>
									<input type="checkbox" name="is_contamination" value="1" class="minimal">&nbsp;
									</label> @lang('equicare.is_contamination')
							</div>
						</div>
						<div class="form-group col-md-12">
							<input type="hidden" name="equip_id" id="equip_id" value="{{old('equip_id')}}" />
							<input type="hidden" name="hospital_id" id="hospital_id" value="{{old('hospital_id')}}" />
							<input type="hidden" name="department_id" id="department_id" value="{{old('department_id')}}" />
							<input type="submit" value="@lang('equicare.submit')" class="btn btn-primary btn-flat">
						</div>
					</div>
			</div>
			</form>
		</div>
	</div>
</div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('assets/js/datetimepicker.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
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


		if($('#external').attr('checked') =='checked'){
			$('.report_no').css('display','block');
		}
		$('#external').on('ifChecked ifUnchecked',function(e){
			if(e.type == 'ifChecked'){
				$('.report_no').show();
			}else{
				$('.report_no').hide();
			}
		})

	});
	$('.call_register_date_time').datetimepicker({
		sideBySide: true,
	})
	@if ($errors->any())
				
        const setSelectValues = () => {
            if ("{{ old('hospital_id') }}") {
                $('.hospital_select2').val("{{ old('hospital_id') }}").trigger('change'); 
            }   
            if ("{{ old('department_id') }}" && "{{ old('hospital_id') }}" == '') {
                $('.department_select2').val("{{ old('department_id') }}").trigger('change'); 
            }   
            if ("{{ old('equip_id') }}" && "{{ old('hospital_id') }}" == '' && "{{ old('department_id') }}" == '') {
                $('.unique_id_select2').val("{{ old('equip_id') }}").trigger('change');  
            }  
        };
				setTimeout(() => {
                setSelectValues();
        }, 500);
  @endif

	$('.unique_id_select2').on('change',function(){
		var value = $(this).val();
		// console.log(value,'test');
		$('#equip_id').val(value);
		var equip_name = $('.equip_name');
		var hospitals = $('.hospital_select2');
		var sr_no = $('.sr_no');
		var company = $('.company');
		var model = $('.model');
		var department = $('.department_select2');
		var workingStatus = $('select[name="working_status"]');

		if(value==""){
			equip_name.val("");
			sr_no.val("");
			company.val("");
			model.val("");
			department.val("");
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

						workingStatus.empty().append(
                    '<option value="">Select Status</option>' +
                    '<option value="working">Working</option>' +
                    '<option value="not working">Not Working</option>' +
                    '<option value="pending">Pending</option>'
                );

                workingStatus.val(data.success.working_status);
				
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
				$('#hospital_id').val(value);
				var equip_name = $('.equip_name');
				var hospitals = $('.hospital_select2');
				var department = $('.department_select2');
				var unique_id = $('.unique_id_select2');
				var sr_no = $('.sr_no');
				var company = $('.company');
				var model = $('.model');
				if(value==""){
					equip_name.val("");
					sr_no.val("");
					company.val("");
					model.val("");
					department.val("");
					unique_id.trigger("change");
					unique_id.val("");

				}
				if(value !=""){
					$.ajax({
						url : "{{ url('hospital_breakdown')}}" ,
						type : 'get',

						data:{'id' : value },
						success:function(data){
							// console.log(data);
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
						 if ("{{ old('department_id') }}") {
                $('.department_select2').val("{{ old('department_id') }}").trigger('change'); 
            } 
				 }
				});
				}
			});


	$('.department_select2').on('change',function(){
				var value = $(this).val();
				$('#department_id').val(value);
				var equip_name = $('.equip_name');
				var hospitals = $('.hospital_select2');

				var unique_id = $('.unique_id_select2');
				var sr_no = $('.sr_no');
				var company = $('.company');
				var model = $('.model');
				if(value==""){
					equip_name.val("");
					sr_no.val("");
					company.val("");
					model.val("");
					$(this).val("");
					unique_id.trigger("change");
					unique_id.val("");

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
				     	placeholder: 'Select an option',
				     	allowClear: true
				     });
				     $('.hospital_select2').select2({
				     	placeholder: 'Select an option',
				     	allowClear: true
				     });
				     $('.department_select2').select2({
				     	placeholder: '{{__("equicare.select_option")}}',
				     	allowClear: true
				     });
						 if ("{{ old('equip_id') }}") {
                $('.unique_id_select2').val("{{ old('equip_id') }}").trigger('change');  
           		}  

				 }
				});
				}
			});

</script>
<script type="text/javascript">
	$.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
</script>
@endsection
@section('styles')
<link rel="stylesheet" type="text/css"
	href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection