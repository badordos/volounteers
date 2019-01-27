<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1920">
    <meta name="developer" content="flagstudio.ru">
    <meta name="cmsmagazine" content="3a145314dbb5ea88527bc9277a5f8274">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dreamachine</title>

    <link rel="apple-touch-icon" sizes="57x57" href="/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="36x36"  href="/images/favicon/android-icon-36x36.png">
    <link rel="icon" type="image/png" sizes="48x48"  href="/images/favicon/android-icon-48x48.png">
    <link rel="icon" type="image/png" sizes="72x72"  href="/images/favicon/android-icon-72x72.png">
    <link rel="icon" type="image/png" sizes="96x96"  href="/images/favicon/android-icon-96x96.png">
    <link rel="icon" type="image/png" sizes="144x144"  href="/images/favicon/android-icon-144x144.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon/favicon-96x96.png">
    <link rel="manifest" href="/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/images/favicon/ms-icon-144x144.png">
    <meta name="msapplication-config" content="/images/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
@auth
    @if(auth()->user()->can('admin'))
        @include('parts.admin_bar')
    @endif
@endauth
<div class="preloader active">
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>
    <div class="circle circle-3"></div>
</div>
<div class="main-wrapper">
    <div class="layout">
        <header class="header @auth @elseif(Route::currentRouteName() != 'main') header--margin @endauth">
            <div class="header__inner @if(Route::currentRouteName() == 'public-profile' || Route::currentRouteName() == 'profile.about-me' || Route::currentRouteName() == 'profile.achievements' || Route::currentRouteName() == 'profile.campaigns' || Route::currentRouteName() == 'profile.settings') header__inner--end @endif">
                @if(Route::currentRouteName() != 'profile.about-me' && Route::currentRouteName() != 'profile.achievements' && Route::currentRouteName() != 'profile.campaigns' && Route::currentRouteName() != 'profile.settings' && Route::currentRouteName() != 'about-me' && Route::currentRouteName() != 'public-profile')

                    <a class="logo header__logo" href="/">
                        <img src="{{ asset('images/logo.svg') }}" alt="Dreamachine">
                    </a>
                @endif

                @auth

                    @if(Route::currentRouteName() != 'main.guest')
                    <nav class="main-nav
                        @if(Route::currentRouteName() == 'profile.about-me' || Route::currentRouteName() == 'profile.achievements' || Route::currentRouteName() == 'profile.campaigns' || Route::currentRouteName() == 'profile.settings' || Route::currentRouteName() == 'public-profile')
                            main-nav--personal
                        @endif ">
                        <ul>
                            <li>
                                <a href="{{ route('campaigns') }}">Campaigns</a>
                            </li>
                            <li>
                                <a href="{{ route('vote') }}">Vote</a>
                            </li>
                            <li>
                                <a href="{{ route('about-us') }}">About us</a>
                            </li>
                            <li>
                                <a href="#!">DREAMING<br>app</a>
                            </li>
                        </ul>
                    </nav>

                    {{--Challenges--}}
                    <auth-user challenges-title="New month challenge"
                               avatar="
                               @if(Auth::user()->image !== null)
                                    /storage/{{ Auth::user()->image }}
                               @else
                                    {{ asset('images/robot1.svg') }}
                               @endif"
                               nick-name="{{auth()->user()->name}}"
                               :note-of-achievements="{{auth()->user()->achievmentsArray()}}"
                               link="{{route('profile.about-me')}}">
                        <ol>
                            <li>
                                <a target="_blank" href="{{ route('profile.achievements') }}">
                                    New month challenge
                                </a>
                                <span class="decoration-checkbox decoration-checkbox--checked"></span>
                            </li>
                            <li>
                                <a target="_blank" href="{{ route('profile.achievements') }}">
                                    New month challenge
                                </a>
                                <span class="decoration-checkbox"></span>
                            </li>
                            <li>
                                <a target="_blank" href="{{ route('profile.achievements') }}">
                                    New month challenge
                                </a>
                                <span class="decoration-checkbox"></span>
                            </li>
                        </ol>

                        {{--Logout--}}
                        <a href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn auth-user__avatar-link" slot="link">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                    </auth-user>
                    @endif
                @else
                    <auth-block :is-active="isActiveAuthBlock">

                        <transition name="fade">
                            <a class="btn" href="#!" @click.prevent="isActiveAuthBlock = !isActiveAuthBlock" v-if="!isActiveAuthBlock">Sign-in</a>
                        </transition>

                        <transition name="fade">
                            <div class="auth-block__view" v-show="isActiveAuthBlock">

                                {{--Authorisation--}}
                                <form method="POST"  action="{{ route('login') }}">
                                    <h4>Sign-in</h4>

                                    {{--Email--}}
                                    @if ($errors->has('email'))
                                        <span class="auth-block__warning">{{ $errors->first('email') }}</span>
                                    @endif
                                    <input name="email" type="email" placeholder="Email" value="{{ old('email') }}" required>

                                    {{--Password--}}
                                    @if ($errors->has('password'))
                                        <span class="auth-block__warning">{{ $errors->first('password') }}</span>
                                    @endif
                                    <input name="password" type="password" placeholder="Password" required>

                                    <div class="auth-block__note">
                                        Not a member? <a href="{{route('register')}}">Sign-up</a>
                                    </div>
                                    <div class="auth-block__note">
                                        Forgot your password? <a href="{{ route('password.request') }}">Reset</a>
                                    </div>
                                    <button class="btn" type="submit">Sign-in</button>

                                    @csrf
                                    <input id="screen" name="screen" type="hidden" value="">
                                </form>

                            </div>
                        </transition>
                    </auth-block>

                    <button class="cmn-toggle-switch" @click="toggleMenu" :class="toggleClassMenu">
                        <span>toggle menu</span>
                    </button>
                    @endauth
            </div>

            <cookies></cookies>
            <transition name="alert">
                <alert-error v-if="activeErrorAlert"></alert-error>
            </transition>

        </header>

@yield('content')

    </div>
    <footer class="footer">
        <div class="footer__inner">

            <a class="logo footer__logo" href="/">
                <img src="{{ asset('images/logo.svg') }}" alt="Dreamachine">
            </a>

            <p class="copyright">
                &copy; 2018 by DreaMachine Foundation
            </p>

            <div class="social">
                <h2>Follow us:</h2>
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

            <div class="privacy-policy">
                <ul>
                    <li>
                        <a target="_blank" href="/privacy-policy.pdf">Privacy Policy</a>
                    </li>
                    <li>
                        <a target="_blank" href="/terms-of-service.pdf">Terms of Service</a>
                    </li>
                </ul>
            </div>
        </div>

    </footer>
</div>

@include('sprite')

<script src="{{ mix('/js/app.js') }}"></script>
{{--Jira Bug Collector--}}
<script type="text/javascript" src="https://jira.flagstudio.ru/s/95ad89360eec1845f13b7a13ded5c0c4-T/-y6xh9q/73015/19eec8c46095745849ebdd927f182f88/2.0.23/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=ru-RU&collectorId=0a832046"></script>
{{--Jira Bug Collector--}}

</body>
</html>