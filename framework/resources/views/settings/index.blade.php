@extends('layouts.admin')
@section('body-title')
@lang('equicare.settings')
@endsection
@section('title')
| @lang('equicare.settings')
@endsection
@section('breadcrumb')
<li class="active">@lang('equicare.settings')</li>
@endsection
@section('styles')
<style type="text/css">
.mt-2 {
    margin-top: 10px;
}

.mb-2 {
    margin-bottom: 10px;
}

select {
    width: 128px;
}
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">
                    @lang('equicare.settings')
                </h4>
            </div>
            <div class="box-body">
                @include ('errors.list')
                {!! Form::open(['url'=>'admin/settings','method'=>'POST','files'=>true]) !!}
                <div class="row">
                    <div class="form-group col-md-6">
                        {!! Form::label('company',__('equicare.company')) !!}
                        {!! Form::text('company',old('company')??$setting->company??null,['class' => 'form-control'])
                        !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('logo',__('equicare.logo_upload')) !!}
                        {!! Form::file('logo',['class' => 'form-control']) !!}
                        @if($setting != null)
                        @if (file_exists('uploads/'.$setting->logo) == true && $setting->logo != null)
                        <div class="mt-2">
                            <img src="{{ asset('uploads/'.$setting->logo) }}" height="100px" width="100px"
                                class="img-thumbnail">
                            <button type="button" onclick="" class="btn btn-danger del-img btn-sm"
                                title="@lang('equicare.delete_logo')"><i class="fa fa-trash"></i></button>
                        </div>
                        @endif
                        @endif
                    </div>
                    <!-- <div class="form-group col-md-6">
                        {!! Form::label('language',__('equicare.language')) !!}
                        <select name="language" class="form-control" id="">
                            <option value="en" @if(env('locale') && env('locale')=='en' ) selected @endif>
                                @lang('equicare.en')</option>
                            <option value="pt" @if(env('locale') && env('locale')=='pt' ) selected @endif>
                                @lang('equicare.pt')</option>
                        </select>
                    </div> -->


                    <div class="form-group col-md-12">
                        {!! Form::submit(__('equicare.submit'),['class' => 'btn btn-primary btn-flat']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">
                    @lang('equicare.smtp_settings')
                </h4>
            </div>
            <div class="box-body">
                @if ($errors->hasBag('mail_errors'))
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="alert alert-danger alert-dismisable">
                            <button class="close" data-dismiss="alert">&times;</button>
                            <ul class="">
                                @foreach ($errors->mail_errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
                {!! Form::open(['url'=>'admin/mail-settings','method'=>'POST']) !!}
                <div class="row">
                    <div class="form-group col-md-6">
                        {!! Form::label('mail_driver',__('equicare.mail_driver')) !!}
                        {!! Form::text('mail_driver',old('mail_driver')??env('MAIL_DRIVER')??null,['class' =>
                        'form-control']) !!}
                    </div>
                    <!-- <div class="form-group col-md-6">
                        {!! Form::label('mail_host',__('equicare.mail_host')) !!}
                        {!! Form::text('mail_host',env('MAIL_HOST')??null,['class' => 'form-control']) !!}
                    </div> -->

                    <div class="form-group col-md-6">
                        {!! Form::label('mail_host', __('equicare.mail_host')) !!}
                        {!! Form::text('mail_host', env('MAIL_HOST') ?? null, ['class' => 'form-control', 'id' => 'mail_host']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('mail_port',__('equicare.mail_port')) !!}
                        {!! Form::text('mail_port',env('MAIL_PORT')??null,['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('mail_username',__('equicare.mail_username')) !!}
                        {!! Form::text('mail_username',env('MAIL_USERNAME')??null,['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('mail_encryption',__('equicare.mail_enc')) !!}
                        {!! Form::text('mail_encryption',env('MAIL_ENCRYPTION')??null,['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('mail_password',__('equicare.mail_pwd')) !!}
                        <input type="password" name="mail_password" value="{{ env('MAIL_PASSWORD')??"" }}"
                            class="form-control" id="mail_password" autocomplete="off" />
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::submit(__('equicare.submit'),['class' => 'btn btn-primary btn-flat']) !!}
                    </div>
                </div>
                {!! Form::close() !!}

            </div>
        </div>

        <!-- <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">
                    @lang('equicare.date_settings')
                </h4>
            </div>
            <div class="box-body">
                @if ($errors->hasBag('date_errors'))
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="alert alert-danger alert-dismisable">
                            <button class="close" data-dismiss="alert">&times;</button>
                            <ul class="">
                                @foreach ($errors->mail_errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
                {!! Form::open(['url'=>'admin/date-settings','method'=>'POST']) !!}
                <div class="row">

                    <div class="form-group col-md-6">
                        {!! Form::label('date_settings',__('equicare.date_settings')) !!}
                        <br>

                        <select name="date_settings" required>
                            <option value="">--Select--</option>
                            <option value="dd-mm-yyyy,d-m-Y" {{env('date_settings')=='dd-mm-yyyy' ? 'selected' : '' }}>
                                dd-mm-yyyy</option>
                            <option value="mm-dd-yyyy,m-d-Y" {{env('date_settings')=='mm-dd-yyyy' ? 'selected' : ''}}>
                                mm-dd-yyyy</option>
                            <option value="yyyy-mm-dd,Y-m-d" {{env('date_settings')=='yyyy-mm-dd' ? 'selected' : ''}}>
                                yyyy-mm-dd</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::submit(__('equicare.submit'),['class' => 'btn btn-primary btn-flat']) !!}
                    </div>
                </div>
                {!! Form::close() !!}

            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">
                    Favicon Settings
                </h4>
            </div>
            <div class="box-body">
                <div class="row">
                    <form method="POST" action="{{ route('store.favicon') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group col-md-6">
                            <label class="form-label" for="favicon">Favicon</label>
                            <input type="file" class="form-control" name="favicon" id="favicon" required>
                        </div>
                        <div class="form-group col-md-12">
                            <input type="submit" class="btn btn-primary" value="Upload">
                        </div>
                    </form>
                </div>
            </div>
        </div> -->


        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">@lang('equicare.general_settings')</h4>
            </div>
                <div class="box-body">
                    <form method="POST" action="{{ route('store.favicon') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label class="form-label" for="date_settings">@lang('equicare.date_settings')</label>
                                <select name="date_settings" id="date_settings" class="form-control" required>
                                    <option value="">--Select--</option>
                                    <option value="dd-mm-yyyy,d-m-Y" {{ env('date_settings') == 'dd-mm-yyyy' ? 'selected' : '' }}>
                                        dd-mm-yyyy
                                    </option>
                                    <option value="mm-dd-yyyy,m-d-Y" {{ env('date_settings') == 'mm-dd-yyyy' ? 'selected' : '' }}>
                                        mm-dd-yyyy
                                    </option>
                                    <option value="yyyy-mm-dd,Y-m-d" {{ env('date_settings') == 'yyyy-mm-dd' ? 'selected' : '' }}>
                                        yyyy-mm-dd
                                    </option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label" for="favicon">@lang('equicare.favicon')</label>
                                <input type="file" class="form-control" name="favicon" id="favicon" accept="image/*">
                                
                            </div>
                        </div>
                        <div class="row">

                        <div class="form-group col-md-6">
                            <label class="form-label" for="allow_guest">@lang('equicare.allow_guest')</label>
                            <div class="checkbox">
                                <input type="checkbox" name="allow_guest" value="1" id="allow_guest" {{ isset($setting) && $setting->allow_guest == 1 ? 'checked' : '' }}>
                                <label for="allow_guest">Enable guest breakdown entries</label>
                            </div>
                        </div>

                            @if($setting != null)
                        @if (file_exists('uploads/'.$setting->favicon) == true && $setting->favicon != null)
                        <div class="mt-1">
                            <img src="{{ asset('uploads/'.$setting->favicon) }}" height="100px" width="100px"
                                class="img-thumbnail">
                            <button type="button" onclick="" class="btn btn-danger del-favimg btn-sm"
                                title="@lang('equicare.delete_logo')"><i class="fa fa-trash"></i></button>
                        </div>
                        @endif
                        @endif

                            <div class="form-group col-md-12">
                                <input type="submit" class="btn btn-primary" value="Upload">
                            </div>

                        </div>
                    </form>
                </div>
            </div>

    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
$(function() {
    $('select').select2();
    $('#mail_password').val("{{ env('MAIL_PASSWORD')??"" }}");
    $('.del-img').on('click', function() {
        @if($setting != null && $setting -> logo != null)
        if (confirm("{{ __('equicare.are_you_sure') }}")) {

            window.location.href = "{{ url('admin/delete_logo',$setting->logo) }}";
        }
        @endif
    });

    $('.del-favimg').on('click', function(){
        @if($setting != null && $setting -> favicon != null)
        if(confirm("{{ __('equicare.are_you_sure') }}")){
            window.location.href = "{{ url('admin/delete-fav_logo',$setting->favicon) }}";
        }
        @endif
    });
    
});
</script>
@endsection