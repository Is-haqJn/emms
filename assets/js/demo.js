$(document).ready(function(){
    $(document).on('ifUnchecked ifChecked', '#check_all', function (event) {
		if (event.type == "ifChecked") {   
	    	$('.moduel_checkbox input:checkbox').prop('checked',true).iCheck('update');
		}else{
			$('.moduel_checkbox input:checkbox').prop('checked',false).iCheck('update');
		}
	});
	$('#data_table').DataTable();
	 $('.color-red').iCheck('destroy');
        setTimeout(function() {
            $('.color-red').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_minimal-red',
                // increaseArea: '20%' /* optional */
            });
        }, 200);
        $(document).on('ifChanged','.select_module', function() {
            let type = $(this).data('type');
            let isChecked = $(this).is(':checked');
            $('input[type="checkbox"][data-type="' + type + '"]').prop('checked', isChecked).iCheck('update');
        });
        // $(document).on('ifChanged','.no-check',function(){
        //     console.log('inside no check');
        //     $(this).prop('checked',true).iCheck('update');
        //     // return;
        // });
        $(document).on('ifChanged', '.no-check', function(){
            console.log($(this));
            setTimeout(()=>{
            $(this).prop('checked', true).iCheck('update');
            $(this).parent('.icheckbox_square-blue').attr('checked');
            },1);
        });
});

