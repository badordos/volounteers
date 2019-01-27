@extends('layouts.dm')
@section('content')

    <section class="personal-menu__inner">
        <a class="logo personal-menu__logo" href="/">
            <img src="{{ asset('images/logo--white.svg') }}" alt="Dreamachine">
        </a>

        <nav class="personal-menu">
            <ul>
                <li class="active">
                    <a href="{{route('public-profile', $user)}}">{{$user->name}}</a>
                </li>
                <li>
                    <a href="scrollMyCampaigns">Created campaigns</a>
                </li>
                <li>
                    <a href="scrollMyAchievements">Achievements</a>
                </li>
                {{--<li>--}}
                    {{--<a href="{{ route('profile.achievements') }}">Achievements</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                    {{--<a href="{{ route('profile.campaigns') }}">Campaigns</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                    {{--<a href="{{ route('profile.settings') }}">Settings</a>--}}
                {{--</li>--}}
            </ul>
        </nav>
    </section>
    <div class="main personal-page ">
        <div class="main__inner">
            <personal-user name="{{json_encode($user->name)}}"
                           location="{{$city = $city ? json_encode($city->title) : ''}}" id="scrollMy"
                           photo="
                            @if($user->image !== null)
                                   /storage/{{ $user->image }}
                           @else
                                {{ asset('images/robot1.svg') }}
                           @endif">
                <template slot="social">
                    <div class="social personal-user__social">
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
                <personal-feature>
                    <h4 slot="title">Age</h4>
                    <p slot="description">{{$age}} years</p>
                </personal-feature>
                <personal-feature>
                    <h4 slot="title">Education</h4>
                    @if(isset($user->education))
                        @foreach((json_decode($user->education, true)) as $education)
                            <p slot="description">
                                {{$education}}
                            </p>
                        @endforeach
                    @endif
                </personal-feature>
                <personal-feature :list="true">
                    <h4 slot="title">Skills</h4>
                    <template slot="list">
                        @if(isset($skills))
                            @foreach($skills as $skill)
                                <personal-list-item>
                                    {{$skill->title}}
                                </personal-list-item>
                            @endforeach
                        @endif
                    </template>
                </personal-feature>
                <personal-feature :list="true">
                    <h4 slot="title">Hobbies</h4>
                    <template slot="list">
                        @if(isset($user->hobbies))
                            @foreach((json_decode($user->hobbies, true)) as $hobbie)
                                <personal-list-item>
                                    {{$hobbie}}
                                </personal-list-item>
                            @endforeach
                        @endif
                    </template>
                </personal-feature>
                <personal-feature :list="true">
                    <h4 slot="title">Interested categories</h4>
                    <template slot="list">
                        @if(isset($categories))
                            @foreach($categories as $category)
                                <personal-list-item>
                                    {{$category->title}}
                                </personal-list-item>
                            @endforeach
                        @endif
                    </template>
                </personal-feature>

            </personal-user>


            <about-campaigns id="scrollMyCampaigns">
                <template slot="filter">
                    @include('parts.campaigns_filter', ['flags' => 'filter', 'title' => 'Campaigns', 'verification' => '',  'disabledBtnFilter' => 'true'])
                </template>
                <template slot="campaigns">
                    @forelse($campaigns as $campaign)
                        <campaign-card title="{{$campaign->title}}"
                                       description="{{str_limit($campaign->description, $limit = 170, $end = '...')}}"
                                       image="{{$src = $campaign->getFirstMedia('preview_image') ? $campaign->getFirstMedia('preview_image')->getUrl('small') : ''}}"
                                       link="{{route('single-campaign', $campaign)}}"
                                       :activated="true">
                            <div class="campaign-card__top">
                                    <span class="campaign-card__top-icon">
                                        <svg>
                                            <use xlink:href="#icon-human"/>
                                        </svg>
                                    </span>
                                <span class="campaign-card__top-text">
                                        {{$volunteers = $campaign->joinedUsers->count()}} volunteers | {{$percent = $campaign->volunteers_needed != 0 ? round($volunteers / $campaign->volunteers_needed * 100, 2) . '%' : ''}}
                                    </span>
                                <span class="campaign-card__verification verified">
                                        <span class="verified">Verified</span>
                                        <span class="not-verified">Not verified</span>
                                    </span>
                            </div>
                        </campaign-card>
                    @empty
                        <p>No campaigns</p>
                    @endforelse
                </template>
            </about-campaigns>


            <achievements id="scrollMyAchievements">
                <h1 slot="title">Achievements</h1>
                @forelse($achievements as $achievement)
                    <achievement-card medal="
                        @if(isset($achievement->image))
                            /storage/{{$achievement->image}}
                    @else
                    {{ asset('/images/achievement.svg') }}
                    @endif ">
                        <h3 slot="title">{{$achievement->title}}</h3>
                        {{--<p slot="note">Take part of 20 Campaigns</p>--}}
                        <p slot="description">
                            {{$achievement->description}}
                        </p>
                    </achievement-card>
                @empty
                    <p>Empty</p>
                @endforelse
            </achievements>

        </div>
    </div>
@endsection