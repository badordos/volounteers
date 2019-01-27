@extends('layouts.dm')
@section('content')

    <div class="main">
            <div class="main__inner">

                <test title="Do you want to start volounteering
                    and help those in need, but
                    do not know how to start?"
                      description="Answer 3 simple questions and we will help you to make choice"
                      route="{{route('testCharity')}}">
                    @foreach ($test as $question)
                        <test-question title="{{$question['title']}}">
                            @foreach($question['variants'] as $variant)
                                <button class="btn btn--question" type="button"
                                        value="{{$variant}}">{{$variant}}</button>
                            @endforeach
                        </test-question>
                    @endforeach
                    <test-finish>
                        <h1>
                            Thank you for your responses. Based on your answers, we can offer you the direction of
                            @{{ testCategory }}
                        </h1>
                    </test-finish>
                </test>

                <campaigns>
                    @foreach ($categories as $category)
                        <campaign name="{{$category->title}}"
                                  @if ($loop->first)
                                  :selected="true"
                                @endif>
                            <div class="main-directions__image"
                                 @if($category->image !== null)
                                 style="background-image: url('/storage/{{ $category->image }}');"
                                    @endif >
                            </div>
                            <div class="main-directions__text">
                                <h3>{{$category->title}} campaigns</h3>
                                <p>{{$category->description}}</p>
                                <a class="btn btn--bold" href="{{$category->link}}">Read more...</a>
                            </div>
                        </campaign>
                    @endforeach
                </campaigns>

            </div>

        <section class="main-sliders">

            <div class="main-sliders__inner">

                <section class="campaigns-slider">
                    <div class="campaigns-slider__left">
                        @if(isset($campaignsSliderTitle))
                            {!! $campaignsSliderTitle->content !!}
                        @endif
                        @if(isset($campaignsSliderDesc))
                            {!! $campaignsSliderDesc->content !!}
                        @endif
                        <ul class="slider-buttons">
                            <li>
                                <button type="button" class="slider-buttons__prev campaigns-slider__prev"></button>
                            </li>
                            <li>
                                <button type="button" class="slider-buttons__next campaigns-slider__next"></button>
                            </li>
                        </ul>
                        <a href="{{route('campaigns')}}" class="btn btn--bold">More campaigns</a>
                    </div>
                    <div class="campaigns-slider__right">
                        <slider-campaigns>
                            <!-- slides -->
                            @foreach ($campaigns as $campaign)
                                <swiper-slide>
                                    <campaign-card title="{{$campaign->title}}"
                                                   description="{{str_limit($campaign->description, $limit = 170, $end = '...')}}"
                                                   image="{{$src = $campaign->getFirstMedia('main_images') ? $campaign->getFirstMedia('main_images')->getUrl() : ''}}"
                                                   link="{{route('single-campaign', $campaign)}}"
                                                   :slider="true"
                                                   :activated="true">
                                        <div class="campaign-card__top">
                                        <span class="campaign-card__top-icon">
                                            <svg>
                                                <use xlink:href="#icon-coin"/>
                                            </svg>
                                        </span>
                                            <span class="campaign-card__top-text">
                                            {{$volunteers = $campaign->joinedUsers->count()}} volunteers | {{$percent = round($volunteers / $campaign->volunteers_needed * 100, 2)}}%
                                        </span>
                                            <span class="campaign-card__verification verified">
                                            <span class="verified">Verified</span>
                                            <span class="not-verified">Not verified</span>
                                        </span>
                                        </div>
                                    </campaign-card>
                                </swiper-slide>
                        @endforeach
                        <!-- Optional controls -->
                            <div class="swiper-scrollbar" slot="scrollbar"></div>
                        </slider-campaigns>
                    </div>
                </section>

                <section class="stories-slider">
                    <div class="stories-slider__left">
                        @if(isset($storiesSliderTitle))
                            {!! $storiesSliderTitle->content !!}
                        @endif
                        @if(isset($storiesSliderDesc))
                            {!! $storiesSliderDesc->content !!}
                        @endif
                        <ul class="slider-buttons">
                            <li>
                                <button type="button" class="slider-buttons__prev stories-slider__prev"></button>
                            </li>
                            <li>
                                <button type="button" class="slider-buttons__next stories-slider__next"></button>
                            </li>
                        </ul>
                    </div>

                    <slider-stories>

                        <!-- swiper1 -->

                        <stories-output :progress="true">
                            <swiper-slide>
                                <story title="Union people corp." :progress="true">

                                    <app-video source="{{ asset('video/dreamachine.mp4')  }}"></app-video>

                                </story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/photo5.jpg') }}" :progress="true"></story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/photo5.jpg') }}" :progress="true"></story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/photo5.jpg') }}" :progress="true"></story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/photo5.jpg') }}" :progress="true"></story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/photo5.jpg') }}" :progress="true"></story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/photo5.jpg') }}" :progress="true"></story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/photo5.jpg') }}" :progress="true"></story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/photo5.jpg') }}" :progress="true"></story>
                            </swiper-slide>
                        </stories-output>

                        <!-- swiper2 -->
                        <stories-thumbs>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/story1.png') }}"></story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/story2.png') }}"></story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/story3.png') }}"></story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/story4.png') }}"></story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/story5.png') }}"></story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/story6.png') }}"></story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/story1.png') }}"></story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/story2.png') }}"></story>
                            </swiper-slide>
                            <swiper-slide>
                                <story title="Union people corp."
                                       preview="{{ asset('/images/story3.png') }}"></story>
                            </swiper-slide>
                        </stories-thumbs>

                    </slider-stories>
                </section>

            </div>
        </section>

            @if(isset($howItWorks))
                {!! showBlock($howItWorks, 'blocks.how-it-works') !!}
            @endif

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
                <section class="team">
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