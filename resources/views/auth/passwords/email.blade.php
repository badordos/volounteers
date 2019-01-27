@extends('layouts.authorization')

@section('content')

    <div class="main">
        <div class="main__inner">

            <section class="authorization">
                <div class="authorization__inner">

                    <h1>Reset password</h1>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{  session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" aria-label="{{ __('Reset Password') }}">
                        @csrf

                        <label class="label">
                            <h3>E-mail</h3>
                            <input id="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                   name="email" value="{{ old('email') }}" placeholder="Email" required>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </label>

                        <input type="submit" value="Reset" class="btn">
                    </form>
                </div>
            </section>

        </div>
    </div>
@endsection
