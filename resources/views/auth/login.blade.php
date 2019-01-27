@extends('layouts.authorization')

@section('content')
    <div class="main">
        <div class="main__inner">

            <section class="authorization">
                <div class="authorization__inner">

                    <h1>Sign in</h1>
                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                        @csrf

                        @if ($errors->has('verified'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $errors->first('verified') !!}</strong>
                            </span>
                        @endif

                        <label class="label">
                            <h3>E-mail</h3>
                            <input name="email" type="email" value="{{old('email')}}" required placeholder="Email" autofocus>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{!! $errors->first('email') !!}</strong>
                                    </span>
                            @endif
                        </label>
                        <label class="label">
                            <h3>Password</h3>
                            <input name="password" type="password" required placeholder="Password">
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </label>
                        <input type="hidden" name="remember" checked>

                        <div class="authorization__note">
                            Not a member? <a href="{{ route('register') }}">Sign-up</a>
                        </div>
                        <div class="authorization__note">
                            Forgot your password? <a href="{{ route('password.request') }}">Reset</a>
                            {{--{{ route('reset-password') }}--}}
                        </div>
                        <input type="submit" value="Sign-in" class="btn">
                        <input id="screen" name="screen" type="hidden" value="">
                    </form>

                </div>
            </section>

        </div>
    </div>
    <script>document.querySelector("#screen").setAttribute('value', screen.width+'/'+screen.height)</script>

@endsection
