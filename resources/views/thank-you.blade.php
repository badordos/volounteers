@extends('layouts.dm')
@section('content')

    <div class="main">
        <section class="thank-you">
            <div class="thank-you__inner">

                <div class="thank-you__inner-photo">
                    <img src="{{ asset('images/robot1.svg') }}" alt="robot">
                </div>
                <div class="thank-you__content">
                    {!! $block->content !!}
                    <a target="_blank" href="{{route('single-campaign', $campaign)}}" class="btn btn--big reverse">
                        View campaign
                    </a>
                </div>

            </div>
        </section>
    </div>
@endsection