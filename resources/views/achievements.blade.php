@extends('layouts.dm')
@section('content')

    @include('parts.personal_menu')

    <div class="main
            @if(Route::currentRouteName() == 'profile.achievements')
            personal-page
            @endif ">
        <div class="main__inner">

            {{--<achievements id="scrollChallenges" :open-all="true">--}}
                {{--<h1 slot="title">Challenges</h1>--}}

                {{--<achievement-card medal="{{ asset('/images/achievement.svg') }}">--}}
                    {{--<h3 slot="title">Great Volonteer</h3>--}}
                    {{--<p slot="note">Take part of 20 Campaigns</p>--}}
                    {{--<p slot="description">--}}
                        {{--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud--}}
                    {{--</p>--}}
                {{--</achievement-card>--}}
                {{--<achievement-card medal="{{ asset('/images/achievement.svg') }}">--}}
                    {{--<h3 slot="title">Great Volonteer</h3>--}}
                    {{--<p slot="note">Take part of 20 Campaigns</p>--}}
                    {{--<p slot="description">--}}
                        {{--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud--}}
                    {{--</p>--}}
                {{--</achievement-card>--}}
                {{--<achievement-card medal="{{ asset('/images/achievement.svg') }}">--}}
                    {{--<h3 slot="title">Great Volonteer</h3>--}}
                    {{--<p slot="note">Take part of 20 Campaigns</p>--}}
                    {{--<p slot="description">--}}
                        {{--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud--}}
                    {{--</p>--}}
                {{--</achievement-card>--}}
                {{--<achievement-card medal="{{ asset('/images/achievement.svg') }}">--}}
                    {{--<h3 slot="title">Great Volonteer</h3>--}}
                    {{--<p slot="note">Take part of 20 Campaigns</p>--}}
                    {{--<p slot="description">--}}
                        {{--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud--}}
                    {{--</p>--}}
                {{--</achievement-card>--}}
                {{--<achievement-card medal="{{ asset('/images/achievement.svg') }}">--}}
                    {{--<h3 slot="title">Great Volonteer</h3>--}}
                    {{--<p slot="note">Take part of 20 Campaigns</p>--}}
                    {{--<p slot="description">--}}
                        {{--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud--}}
                    {{--</p>--}}
                {{--</achievement-card>--}}
                {{--<achievement-card medal="{{ asset('/images/achievement.svg') }}">--}}
                    {{--<h3 slot="title">Great Volonteer</h3>--}}
                    {{--<p slot="note">Take part of 20 Campaigns</p>--}}
                    {{--<p slot="description">--}}
                        {{--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud--}}
                    {{--</p>--}}
                {{--</achievement-card>--}}
                {{--<achievement-card medal="{{ asset('/images/achievement.svg') }}">--}}
                    {{--<h3 slot="title">Great Volonteer</h3>--}}
                    {{--<p slot="note">Take part of 20 Campaigns</p>--}}
                    {{--<p slot="description">--}}
                        {{--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud--}}
                    {{--</p>--}}
                {{--</achievement-card>--}}
                {{--<achievement-card medal="{{ asset('/images/achievement.svg') }}">--}}
                    {{--<h3 slot="title">Great Volonteer</h3>--}}
                    {{--<p slot="note">Take part of 20 Campaigns</p>--}}
                    {{--<p slot="description">--}}
                        {{--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud--}}
                    {{--</p>--}}
                {{--</achievement-card>--}}
                {{--<achievement-card medal="{{ asset('/images/achievement.svg') }}">--}}
                    {{--<h3 slot="title">Great Volonteer</h3>--}}
                    {{--<p slot="note">Take part of 20 Campaigns</p>--}}
                    {{--<p slot="description">--}}
                        {{--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud--}}
                    {{--</p>--}}
                {{--</achievement-card>--}}
                {{--<achievement-card medal="{{ asset('/images/achievement.svg') }}">--}}
                    {{--<h3 slot="title">Great Volonteer</h3>--}}
                    {{--<p slot="note">Take part of 20 Campaigns</p>--}}
                    {{--<p slot="description">--}}
                        {{--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud--}}
                    {{--</p>--}}
                {{--</achievement-card>--}}
                {{--<achievement-card medal="{{ asset('/images/achievement.svg') }}">--}}
                    {{--<h3 slot="title">Great Volonteer</h3>--}}
                    {{--<p slot="note">Take part of 20 Campaigns</p>--}}
                    {{--<p slot="description">--}}
                        {{--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud--}}
                    {{--</p>--}}
                {{--</achievement-card>--}}
            {{--</achievements>--}}

            <achievements :count="12" id="scrollAllAchievements" :open-all="true">
                <h1 slot="title">All achievements</h1>
                @forelse($achievements as $achievement)
                <achievement-card :big="true" medal="
                    @if(isset($achievement->image))
                        /storage/{{$achievement->image}}
                    @else
                        {{ asset('/images/achievement.svg') }}
                    @endif ">
                    <h3 slot="title">{{$achievement->title}}</h3>
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