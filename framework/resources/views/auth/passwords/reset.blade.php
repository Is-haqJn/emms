@extends('layouts.app')

@section('content')
<div class="login-box">
  <div class="login-logo">

    <a href="{{ url('/') }}"><img src="{{ asset('assets/1x/login-logo.png') }}"></a>
  </div>
  <!-- /.login-logo -->
        <div class="login-box-body">
            <form method="POST" action="{{ route('password.request') }}" aria-label="{{ __('Reset Password') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group has-feedback">
                    <label for="email" class="col-form-label text-md-right">{{ __('E-Mail Address') }}</label>


                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif

                </div>

                <div class="form-group has-feedback">
                    <label for="password" class="col-form-label text-md-right">{{ __('Password') }}</label>


                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif

                </div>

                <div class="form-group has-feedback">
                    <label for="password-confirm" class="col-form-label text-md-right">{{ __('Confirm Password') }}</label>


                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary btn-flat">
                            @lang('equicare.reset_password')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
