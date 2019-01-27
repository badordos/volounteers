@extends('layouts.dm')

@section('content')
<section class="verification">
    <div class="verification__inner">

        <h1>{{ __('Verify Your Email Address') }}</h1>
        <p>
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            {{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
        </p>

    </div>
</section>
@endsection
