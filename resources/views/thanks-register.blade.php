@extends('layouts.dm')
@section('content')
    <div class="main">
        <section class="thank-you">
            <div class="thank-you__inner">

                <div class="thank-you__inner-photo">
                    <img src="{{ asset('images/robot1.svg') }}" alt="robot">
                </div>
                <div class="thank-you__content">
                    @if(isset($block))
                        {!! $block->content !!}
                    @endif
                    <a href="{{route('main')}}" class="btn">Go to main</a>
                </div>

            </div>
        </section>
    </div>
@endsection