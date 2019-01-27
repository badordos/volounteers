@extends('layouts.dm')
@section('content')

    <div class="main">
        <div class="main__inner">
            {{--@include('parts.campaigns_filter', ['flags' => 'row', 'title' => 'Vote', 'verification' => 'verification'])--}}


            <vote-list text-btn="More votes">

                @foreach($votings as $voting)
                    <vote-card
                            @if($voting->users->contains(Auth::user()))
                            :disabled="true"
                            @endif
                            title="{{str_limit($voting->title, $limit = 50, $end = '...')}}"
                            description="{{str_limit($voting->description, $limit = 140, $end = '...')}}"
                            image="{{$src = $voting->step->campaign->getFirstMedia('preview_image') ? $voting->step->campaign->getFirstMedia('preview_image')->getUrl('small') : ''}}"
                            popup-title="{{str_limit($voting->title, $limit = 40, $end = '...')}}"
                            popup-description="{{str_limit($voting->description, $limit = 170, $end = '...')}}"
                            popup-image="{{$src = $voting->step->campaign->getFirstMedia('preview_image') ? $voting->step->campaign->getFirstMedia('preview_image')->getUrl() : ''}}"
                            :popup-variants="{{$voting->variantsArray()}}"
                            route="{{route('voting', ['voting_id' => $voting->id, 'user_id' => $user = auth()->check() ? auth()->user()->id : '' ])}}">

                        <template slot="social">
                            <div class="social">
                                <ul>
                                    <li>
                                        <a target="_blank" href="#!">
                                            <svg>
                                                <use xlink:href="#icon-fb" />
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="#!">
                                            <svg>
                                                <use xlink:href="#icon-twitter" />
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="#!">
                                            <svg>
                                                <use xlink:href="#icon-google" />
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="#!">
                                            <svg>
                                                <use xlink:href="#icon-reddit" />
                                            </svg>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </template>
                        <template slot="more">
                            <a href="{{route('single-campaign', $voting->step->campaign )}}" class="btn btn--small">Read more...</a>
                        </template>
                        <template slot="button-vote">
                            @if(Auth::check() && $voting->step->campaign->joinedUsers->contains(Auth::user()))
                                <button class="btn btn--vote">Vote</button>
                            @else
                                <a href="{{route('single-campaign', $voting->step->campaign )}}" class="btn btn--vote">Join Now</a>
                            @endif
                        </template>
                    </vote-card>
                @endforeach

            </vote-list>

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

        </div>
    </div>


@endsection