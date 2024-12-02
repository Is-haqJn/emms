$('.qr_line').on('input',function(){
        $('.qr_line_value').val($('.qr_line').val());
    });
    $('.select2_qr').on('change',function(){
        console.log('test',$(this).val());
        switch(Number($(this).val())){
                case 1 :
                qr_size = '400';
                break; 
                case 2 :
                qr_size = '300';
                break; 
                case 3 :
                qr_size = '200';
                break; 
                case 6:
                qr_size = '100';
                break;
                default:
                qr_size = '0';
                break;
            }
            if($(this).val() == ""){
                $('.btn-qr').prop('disabled',true);    
            }else{
                $('.btn-qr').prop('disabled',false);    
                
            }
        $('.qr-size').val(qr_size);
        $('.qr-size').text(qr_size)
            
    });
    
   $('.select2_qr').select2();