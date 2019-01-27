@extends('layouts.dm')
@section('content')

    <section class="personal-menu__inner">
        <a class="logo personal-menu__logo" href="/">
            <img src="{{ asset('images/logo--white.svg') }}" alt="Dreamachine">
        </a>

        <nav class="personal-menu">
            <ul>
                <li>
                    <a href="{{ route('profile.about-me') }}">About me</a>
                </li>
                <li>
                    <a href="{{ route('profile.achievements') }}">Achievements</a>
                </li>
                <li>
                    <a href="{{ route('profile.campaigns') }}">Campaigns</a>
                </li>
                <li class="active">
                    <a href="{{ route('profile.settings') }}">profile.settings</a>
                    <ul>
                        <li>
                            <a href="scrollAccount">Account settings</a>
                        </li>
                        <li>
                            <a href="scrollSocial">Social profiles</a>
                        </li>
                        <li>
                            <a href="scrollNetwork">My network</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </section>
    <div class="main
            @if(Route::currentRouteName() == 'profile.settings')
            personal-page
            @endif ">
        <div class="main__inner">

            <section class="settings settings--editor" id="scrollAccount">
                <div class="settings__inner">

                    <h1>Account settings</h1>

                    <personal-user-editor name="Ross Zaykov" location="Prague">
                        <template slot="column-1">
                            <personal-feature-edit type="date">
                                <h4 slot="title">Age</h4>
                                <app-date-picker></app-date-picker>
                            </personal-feature-edit>
                            <personal-feature-edit type="input">
                                <h4 slot="title">Education</h4>
                                <input-feature></input-feature>
                            </personal-feature-edit>
                            <personal-feature-edit :options="[
                        {
                            id: 1,
                            label: 'Takes care of Animals'
                        },
                        {
                            id: 2,
                            label: 'Organization skills'
                        },
                        {
                            id: 3,
                            label: 'Taken care of kids'
                        },
                        {
                            id: 4,
                            label: 'Communication skills'
                        },
                        {
                            id: 5,
                            label: 'test'
                        },
                        {
                            id: 6,
                            label: 'test'
                        },
                        {
                            id: 7,
                            label: 'test'
                        },
                        {
                            id: 8,
                            label: 'test'
                        }]" :default-values="[1, 2, 3]" type="list">
                                <h4 slot="title">Skills</h4>
                            </personal-feature-edit>
                        </template>

                        <template slot="column-2">
                            <personal-feature-edit type="input">
                                <h4 slot="title">Hobbies</h4>
                                <input-feature></input-feature>
                            </personal-feature-edit>
                            <personal-feature-edit :options="[
                        {
                            id: 1,
                            label: 'Education'
                        },
                        {
                            id: 2,
                            label: 'Nature'
                        },
                        {
                            id: 3,
                            label: 'Science'
                        },
                        {
                            id: 4,
                            label: 'test'
                        },
                        {
                            id: 5,
                            label: 'test'
                        },
                        {
                            id: 6,
                            label: 'test'
                        },
                        {
                            id: 7,
                            label: 'test'
                        },
                        {
                            id: 8,
                            label: 'test'
                        }]" :default-values="[1, 2, 3]" type="list">
                                <h4 slot="title">Intrested categories</h4>
                            </personal-feature-edit>
                        </template>

                    </personal-user-editor>

                    <div class="settings__item">
                        <label>
                            <h2>Username</h2>
                            <input type="text" value="benefactor_01">
                        </label>
                        <button type="button" class="btn reverse">Change Username</button>
                    </div>

                    <div class="settings__item">
                        <label>
                            <h2>E-mail</h2>
                            <input type="text" value="benefactor@gmail.com">
                        </label>
                        <button type="button" class="btn reverse">Change E-mail</button>
                    </div>

                </div>
            </section>

            <section class="settings settings--password">
                <div class="settings__inner">

                    <h1>Security settings</h1>

                    <div class="settings__item">
                        <label>
                            <h2>Password</h2>
                            <input type="password" placeholder="Old password">
                            <input type="password" placeholder="New password">
                            <input type="password" placeholder="Repeat new password">
                            <button type="button" class="btn reverse">Change password</button>
                        </label>
                    </div>

                </div>
            </section>

            <section class="settings-social" id="scrollSocial">
                <div class="settings-social__inner">

                    <h1>Social profiles</h1>

                    <div class="settings-social__item">
                        <label>
                            <h2>
                                <svg>
                                    <use xlink:href="#icon-fb" />
                                </svg>
                                Facebook
                            </h2>
                            <input type="text" placeholder="https://www.facebook.com/zuck">
                        </label>
                        <button type="button" class="btn-connect btn-connect--fb">
                            <svg>
                                <use xlink:href="#icon-fb" />
                            </svg>
                            Connect to facebook
                        </button>
                    </div>

                    <div class="settings-social__item">
                        <label>
                            <h2>
                                <svg>
                                    <use xlink:href="#icon-fb" />
                                </svg>
                                Twitter
                            </h2>
                            <input type="text" placeholder="https://www.facebook.com/zuck">
                        </label>
                        <button type="button" class="btn-connect btn-connect--tw">
                            <svg>
                                <use xlink:href="#icon-twitter" />
                            </svg>
                            Connect to twitter
                        </button>
                    </div>

                    <div class="settings-social__item">
                        <label>
                            <h2>
                                <svg>
                                    <use xlink:href="#icon-google" />
                                </svg>
                                WeChat
                            </h2>
                            <input type="text" placeholder="https://www.facebook.com/zuck">
                        </label>
                        <button type="button" class="btn-connect btn-connect--google">
                            <svg>
                                <use xlink:href="#icon-google" />
                            </svg>
                            Connect to WeChat
                        </button>
                    </div>

                    <div class="settings-social__item">
                        <label>
                            <h2>
                                <svg>
                                    <use xlink:href="#icon-reddit" />
                                </svg>
                                Reddit
                            </h2>
                            <input type="text" placeholder="https://www.facebook.com/zuck">
                        </label>
                        <button type="button" class="btn-connect btn-connect--reddit">
                            <svg>
                                <use xlink:href="#icon-reddit" />
                            </svg>
                            Connect to reddit
                        </button>
                    </div>
                </div>
            </section>

            <section class="settings-network" id="scrollNetwork">
                <h1>My network</h1>

                <div class="settings-network__inner">
                    <div class="settings-network__inner-column">
                        <h2>
                            Send me:
                        </h2>

                        <ul>
                            <li>
                                <label>
                                    <input type="checkbox"><span class="checkbox"></span> New campaigns for me
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox"><span class="checkbox"></span> New vote for me
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox"><span class="checkbox"></span> Campaigns near you
                                </label>
                            </li>
                        </ul>
                    </div>
                    <div class="settings-network__inner-column">
                        <h2>
                            Acount activity:
                        </h2>

                        <ul>
                            <li>
                                <label>
                                    <input type="checkbox"><span class="checkbox"></span> Someone follow my campaigns
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox"><span class="checkbox"></span> Someone vote for my campaigns
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox"><span class="checkbox"></span> Someone send comments
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox"><span class="checkbox"></span> Someone share my campaigns
                                </label>
                            </li>
                        </ul>
                    </div>
                    <div class="settings-network__inner-column">
                        <h2>
                            Dreamachine news:
                        </h2>

                        <ul>
                            <li>
                                <label>
                                    <input type="checkbox"><span class="checkbox"></span> Company news
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox"><span class="checkbox"></span> Weekly digest
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            <a href="#!" class="btn btn--delete settings__btn" @click.prevent="deleteAccount">Delete account</a>
        </div>
    </div>
@endsection