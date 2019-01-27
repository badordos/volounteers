<section class="personal-menu__inner">
    <a class="logo personal-menu__logo" href="/">
        <img src="{{ asset('images/logo--white.svg') }}" alt="Dreamachine">
    </a>

    <nav class="personal-menu">
        <ul>
            <li
                    @if(Route::currentRouteName() == 'profile.about-me')
                    class="active"
                    @endif
            ><a href="{{route('profile.about-me') }}">About me</a>
                @if(Route::currentRouteName() == 'profile.about-me')
                    <ul>
                        <li>
                            <a href="scrollMy">My</a>
                        </li>
                        <li>
                            <a href="scrollMyCampaigns">My campaigns</a>
                        </li>
                        <li>
                            <a href="scrollMyAchievements">My achievements</a>
                        </li>
                    </ul>
                @endif
            </li>
            <li
                    @if(Route::currentRouteName() == 'profile.achievements')
                    class="active"
                    @endif
            >
                <a href="{{ route('profile.achievements') }}">Achievements</a>
                @if(Route::currentRouteName() == 'profile.achievements')
                    <ul>
                        {{--<li>--}}
                        {{--<a href="scrollChallenges">Challenges</a>--}}
                        {{--</li>--}}
                        <li>
                            <a href="scrollAllAchievements">All achievements</a>
                        </li>
                    </ul>
                @endif

            </li>
            <li
                    @if(Route::currentRouteName() == 'profile.campaigns')
                    class="active"
                    @endif
            >
                <a href="{{ route('profile.campaigns') }}">Campaigns</a>
                @if(Route::currentRouteName() == 'profile.campaigns')
                    <ul>
                        <li>
                            <a href="scrollCreated">Created</a>
                        </li>
                        <li>
                            <a href="scrollToTake">Campaigns to take part</a>
                        </li>
                    </ul>
                @endif

            </li>
            {{--<li>--}}
            {{--<a href="{{ route('profile.settings') }}">Settings</a>--}}
            {{--</li>--}}
        </ul>
    </nav>
</section>