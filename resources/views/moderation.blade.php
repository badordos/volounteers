@extends('layouts.dm')
@section('content')

    <div class="main">
        <section class="thank-you">
            <div class="thank-you__inner">

                <div class="thank-you__inner-photo">
                    <img src="{{ asset('images/robot1.svg') }}" alt="robot">
                </div>
                <div class="thank-you__content">
                    <h1>Sorry, your last campaign on moderation. You can't create new campaign. Repeat later.</h1>
                </div>

            </div>
        </section>
    </div>
@endsection