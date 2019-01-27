@extends('layouts.dm')
@section('content')

    <div class="main">

        @if(isset($about))

            <section class="about">
                <div class="about__inner">

                    @if(isset($aboutHeader))
                        {!!$aboutHeader->content!!}
                    @endif

                    @foreach($about as $block)
                        {!! showBlock($block, 'blocks.about') !!}
                    @endforeach

                </div>
            </section>
        @endif

        @if(isset($team))
            <section class="team" id="team">
                <div class="team__inner">

                    @if(isset($teamHeader))
                        {!!$teamHeader->content!!}
                    @endif

                    <div class="person__inner">

                        @foreach($team as $member)
                            {!! showBlock($member, 'blocks.member') !!}
                        @endforeach

                    </div>

                </div>
            </section>
        @endif
    </div>
@endsection