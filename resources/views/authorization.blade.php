@extends('layouts.authorization')
@section('content')

    <div class="main">
        <div class="main__inner">

            <section class="authorization">
                <div class="authorization__inner">

                    <h1>Sign in</h1>

                    <form action="#!">
                        <label class="label">
                            <h3>E-mail</h3>
                            <input type="email" required placeholder="Email">
                        </label>
                        <label class="label">
                            <h3>Password</h3>
                            <input type="password" required placeholder="Password">
                        </label>

                        <div class="authorization__note">
                            Not a member? <a href="{{ route('registration') }}">Sign-up</a>
                        </div>
                        <div class="authorization__note">
                            Forgot your password? <a href="{{ route('password.request') }}">Reset</a>
                        </div>
                        <input type="submit" value="Sign-in" class="btn">
                    </form>

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>

                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        </div>
                    </div>

                </div>
            </section>

        </div>
    </div>

@endsection