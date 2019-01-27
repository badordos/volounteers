@extends('layouts.dm')
@section('content')

    <div class="main">
        <registration robot-image="{{ asset('images/robot1.svg') }}"
                      :validation="{
                        'name': 'required|alpha',
                        'email': 'required|email',
                        'password': 'required|alpha_num|min:6'
                      }"
                      :dialog="{
                        'welcome': 'Hello, I am DREAMBOT. Welcome to DREAMMACHINE-Decentralize Volonteer Campaign service.',
                        'focus-name': 'I am DREAMBOT, wat’s your name?',
                        'blur-name': 'Nice to meet you ',
                        'taken-name': 'Sorry this username is already taken(',
                        'focus-email': 'Please write your mail this is important!',
                        'blur-email': 'Wow! Beautifull e-mail!',
                        'focus-pass': 'Oh) I dont look!',
                        'focus-repeat-pass': 'Repeat the password I really do not look)',
                        'warning': 'Please check your details.',
                        'passwords not equal': 'Passwords do not match',
                        'final': 'Congratulation!'
                      }"
                      action="{{ route('register') }}"
                      :old-values="{
                        'name': '{{ old('name') }}',
                        'email': '{{ old('email') }}'
                      }"
                      :errors-data="{
                        'errors': [
                        @if ($errors->has('name'))
                            'name',
                        @endif
                        @if ($errors->has('email'))
                            'email',
                        @endif
                        @if ($errors->has('password'))
                            'password',
                            'repeated-password',
                        @endif
                        ],
                        'text-error':
                        @if ($errors->has('name'))
                            '{{ $errors->first('name') }}'
                        @elseif($errors->has('email'))
                            '{{ $errors->first('email') }}'
                        @elseif($errors->has('password'))
                            '{{ $errors->first('password') }}'
                        @else
                            ''
                        @endif
                      }"
                      :final-screen="false"
        >
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
            <template slot="thank">
                    <h1>Thank you for your registration! Together we can do more)</h1>
                    <a href="{{route('main')}}" class="btn">Go to main</a>
            </template>
        </registration>
    </div>
@endsection