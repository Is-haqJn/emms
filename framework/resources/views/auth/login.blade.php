@extends('layouts.app')

@section('content')
<div class="login-box">
    <div class="login-logo">
    @php($settings = App\Setting::first())
        <!-- <a href="{{ url('/') }}"><img src="{{ asset('assets/1x/login-logo.png') }}"></a> -->
        @if($settings)
    @if($settings->logo != null)
        <img class="" alt="Medical Logo" src="{{ asset('uploads/'.$settings->logo) }}" height="50%" width="50%">
    @elseif($settings->company != null)
        <span class="logo-lg"><b>{{ $settings->company }}</b></span>
    @else
        <!-- <span class="logo-lg"><img src="{{ asset('assets/1x/logo.png') }}"></span> -->
        <a href="{{ url('/') }}"><img src="{{ asset('assets/1x/login-logo.png') }}"></a>
    @endif
@else
    <a href="{{ url('/') }}"><img src="{{ asset('assets/1x/login-logo.png') }}"></a>
@endif
          </span>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
            @csrf

            <div class="form-group has-feedback">
                <label for="email" class="col-form-label text-md-right">@lang('equicare.email_add') </label>
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                    name="email" value="{{ old('email') }}" required autofocus>

                @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif

            </div>

            <div class="form-group has-feedback">
                <label for="password" class="col-form-label text-md-right">@lang('equicare.password') </label>


                <input id="password" type="password"
                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif

            </div>

            <div class="form-group row">
                <div class="col-xs-12">
                    <div class="checkbox icheck login-block">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            @lang('equicare.remember_me')
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-flat pull-right">
                        @lang('equicare.login')
                    </button>
                </div>
            </div>
            <a class="btn btn-link login-padding" href="{{ route('password.request') }}">
                @lang('equicare.forgot_your_password')
            </a>
        </form>
    </div>
</div>

@endsection