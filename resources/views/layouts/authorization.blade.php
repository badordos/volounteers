<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1920">

    <title>Dreamachine</title>

    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
<div class="main-wrapper">
    <div class="layout">
        <header class="header header--margin">
            <div class="header__inner">

                <a class="logo header__logo" href="/">
                    <img src="{{ asset('images/logo.svg') }}" alt="Dreamachine">
                </a>

                <nav class="main-nav main-nav--authorization">
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

            </div>

            <cookies></cookies>
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
        </div>

    </footer>
</div>

@include('sprite')

<script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>