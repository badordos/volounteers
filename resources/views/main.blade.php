@extends('layouts.dm')
@section('content')

    <div class="main @auth main--margin @endauth">

        @auth

            {{--<app-filter :variants="['Prague', 'Moscow', 'Paris', 'Rome', 'New York', 'London']" :flags="['Education', 'Children', 'Nature', 'Health', 'Science']">--}}
            {{--<h1 slot="title">Ongoing campaigns</h1>--}}
            {{--</app-filter>--}}

        @else
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
        @endauth
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
                                                   image="{{$src = $campaign->getFirstMedia('preview_image') ? $campaign->getFirstMedia('preview_image')->getUrl('small') : ''}}"
                                                   link="{{route('single-campaign', $campaign)}}"
                                                   @if($campaign->joinedUsers->contains(auth()->user()))
                                                   text-link="Joined"
                                                   @else
                                                   text-link="Read more"
                                                   @endif
                                                   :slider="true"
                                                   :activated="true">
                                        <div class="campaign-card__top">
                                        <span class="campaign-card__top-icon">
                                            <svg>
                                                <use xlink:href="#icon-human"/>
                                            </svg>
                                        </span>
                                            <span class="campaign-card__top-text">
                                            {{$volunteers = $campaign->joinedUsers->count()}}
                                                volunteers | {{$percent = round($volunteers / $campaign->volunteers_needed * 100, 2)}}
                                                %
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

        @auth
            <section class="vote">
                <div class="vote__inner">

                    @if(isset($votesHeader))
                        {!! $votesHeader->content !!}
                    @endif

                    <slider-vote>
                        @foreach ($votings as $voting)
                            <swiper-slide>
                                <vote-card title="{{str_limit($voting->title, $limit = 50, $end = '...')}}"
                                        @if($voting->users->contains(Auth::user()))
                                        :disabled="true"
                                        @endif
                                           description="{{str_limit($voting->description, $limit = 140, $end = '...')}}"
                                           image="{{$src = $voting->step->campaign->getFirstMedia('preview_image') ? $voting->step->campaign->getFirstMedia('preview_image')->getUrl('small') : ''}}"
                                           popup-title="{{str_limit($voting->title, $limit = 40, $end = '...')}}"
                                           popup-description="{{str_limit($voting->description, $limit = 170, $end = '...')}}"
                                           popup-image="{{$src = $voting->step->campaign->getFirstMedia('preview_image') ? $voting->step->campaign->getFirstMedia('preview_image')->getUrl() : ''}}"
                                           :popup-variants="{{$voting->variantsArray()}}"
                                           route="{{route('voting', ['voting_id' => $voting->id, 'user_id' => $user = auth()->check() ? auth()->user()->id : '' ])}}"
                                           :slider="true"
                                           :activated="true">
                                    <template slot="social">
                                        <div class="social">
                                            <ul>
                                                <li>
                                                    <a target="_blank" href="#!">
                                                        <svg>
                                                            <use xlink:href="#icon-fb"/>
                                                        </svg>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a target="_blank" href="#!">
                                                        <svg>
                                                            <use xlink:href="#icon-twitter"/>
                                                        </svg>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a target="_blank" href="#!">
                                                        <svg>
                                                            <use xlink:href="#icon-google"/>
                                                        </svg>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a target="_blank" href="#!">
                                                        <svg>
                                                            <use xlink:href="#icon-reddit"/>
                                                        </svg>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </template>
                                    <template slot="more">
                                        <a href="{{route('single-campaign', $voting->step->campaign )}}"
                                           class="btn btn--small">Read more...</a>
                                    </template>
                                    <template slot="button-vote">
                                        @if(auth()->check() && $voting->step->campaign->joinedUsers->contains(auth()->user()))
                                            <button class="btn btn--vote">Vote</button>
                                        @else
                                            <a href="{{route('single-campaign', $voting->step->campaign )}}"
                                               class="btn btn--vote">Join Now</a>
                                        @endif
                                    </template>
                                </vote-card>
                            </swiper-slide>
                        @endforeach
                    </slider-vote>
                </div>

                <transition name="fade">
                    <section class="popup" v-show="activePopup" @click="closePopup">
                        <div class="popup__inner" :style="{backgroundImage: 'url(' +  popupContent.image + ')'}">

                            <h1>@{{ popupContent.title }}</h1>
                            <p>@{{ popupContent.description }}</p>

                            <div class="popup__vote">
                                <button type="button" class="btn btn--vote" v-for="item in popupContent.variants"
                                        @click="vote"> @{{ item }}
                                </button>
                            </div>

                            <transition name="fade">
                                <div class="popup__select" v-show="selectedVariant">
                                    <button type="button" class="btn btn--vote" @click="sendVote">Vote</button>
                                </div>
                            </transition>

                            <button type="button" class="btn btn--close popup__close" @click="closeVote"></button>
                        </div>
                    </section>
                </transition>

            </section>

                @if(isset($createCampaign))
                    @include('blocks.create')
                @endif

        @else
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
        @endauth
    </div>

@endsection