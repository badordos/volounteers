@extends('layouts.authorization')

@section('content')

    <div class="main">
        <div class="main__inner">

            <section class="authorization">
                <div class="authorization__inner">

                    <h1>Reset password</h1>

                    <form method="POST" action="{{ route('password.request') }}" aria-label="{{ __('Reset Password') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <label class="label">
                            <h3>{{ __('E-Mail Address') }}</h3>
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </label>

                        <label class="label">
                            <h3>{{ __('Password') }}</h3>
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </label>

                        <label class="label">
                            <h3>{{ __('Confirm Password') }}</h3>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </label>

                        <button type="submit" class="btn">
                            {{ __('Reset Password') }}
                        </button>
                    </form>

                </div>
            </section>

        </div>
    </div>
@endsection
