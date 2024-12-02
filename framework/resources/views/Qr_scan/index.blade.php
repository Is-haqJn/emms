@extends('layouts.admin')
@section('body-title')
@lang('equicare.qr-scan')
@endsection
@section('title')
	| @lang('equicare.qr-scan')
@endsection
@section('styles')
    <style>
        .html5-qrcode-element {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            margin-bottom: 2%;
        }
        .d-flex{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .btn-flat{
            margin-top: 20px;
        }
    </style>
@endsection
@section('breadcrumb')
	<li class=" active">@lang('equicare.qr-scan')</li>
@endsection
@section('content')
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
				<div class="box-header with-border">
					<h4 class="box-title">@lang('equicare.manage-qr-scan')
							{{-- @can('Create qr') --}}
								{{-- <a href="{{ route('qr.create') }}" class="btn btn-primary btn-flat">@lang('equicare.add_new')</a> --}}
							{{-- @endcan --}}
						</h4>
					</div>
					<div class="box-body d-flex">
						<div class="mb-5 d-flex" style="margin-bottom:2%">
                            <div id="scanner_start" width="400px" height="200px" class="text-center text-primary h4 s" >
                                Please Press Start Scan To Start Scanning</div>
                            <button class="btn btn-primary btn-flat" style="text-align: center" id="start_scan">Start Scan </button>
                            <div id="reader"
                                style="display:none;margin-bottom:2%;width:400px;height:400px; min-height: 100px; text-align: center; position: relative;">
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
@endsection
@section('scripts')
<script src="{{ asset('assets/js/html5-qrcode.min.js') }}" type="text/javascript"></script>
    
    <script>
        
        $(function() {
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                }, false);

            function onScanSuccess(decodedText, decodedResult) {

                // handle the scanned code as you like, for example:
                        const url = decodedText;
                        // Define a regular expression to extract the ID
                        const regex = /\/([a-zA-Z0-9]+)$/;
                        // Use match to find matches
                        const matches = url.match(regex);
                        if (matches && matches.length > 1) {
                            const equipmentId = matches[1];  // The ID will be in the first capturing group
                            $('#start_scan').show();
                            $('#scanner_start').show();
                            $('#reader').hide();
                            var route = "{{url('admin/qr-scan/')}}"
                            html5QrcodeScanner.clear();
                            $.ajax({
                              type:'get',
                              url:route+'/'+equipmentId,
                              success:function(data){
                                 if(data.success=='not-assigned'){
                                    location.href= "{{route('equipments.create')}}"+'?qr_id='+data.id;
                                 }
                                 else if(data.success=='assigned'){
                                        location.href = data.url;
                                 }
                                 else{
                                    new PNotify({
                                        title: ' Danger!',
                                        text: data.msg,
                                        type: 'danger',
                                        delay: 3000,
                                        nonblock: {
                                            nonblock: true
                                        }
                                    });
                                 }
                              },
                              error:function(){
                              },

                            });
                        }    
                return true;
            }

            function onScanFailure(error){
            }
            $('#start_scan').on('click', function() {
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                $('#start_scan').hide();
                $('#scanner_start').hide();
                $('#reader').show();
                
            });
        });
       
    </script>
@endsection