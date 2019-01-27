<div id="admin-bar">
    <ul class="admin-bar">
        <li class="admin-bar__icons-flag">
            <a target="_blank" href="/nova">
                <img class="admin-bar__icons-first" src="{{ asset('images/flag_logo-1.svg')  }}" alt="flagstudio.ru">
            </a>

            <ul class="admin-bar__submenu">
                <li><a target="_blank" href="https://laravel.com/docs/master/eloquent-collections">Laravel Docs</a></li>
                <li><a target="_blank" href="https://nova.laravel.com/docs/1.0/resources/fields.html">Nova Docs</a></li>
                <li><a target="_blank" href="https://flagstudio.ru">Flag Studio</a></li>
            </ul>
        </li>
        <li>
            <a href="{{route('main.guest')}}">Edit main page for guests
                {{--<svg class="admin-bar__icon">--}}
                    {{--<use xlink:href="#icon-pencil"/>--}}
                {{--</svg>--}}
            </a>
        </li>
            @if(request()->routeIs('single-campaign'))
                <li>
                    <a href="{{url('/nova/resources/campaigns/') . '/' . $campaign->id}}">Edit Campaign</a>
                </li>
            @endif
            @if(request()->routeIs('public-profile'))
                <li>
                    <a href="{{url('/nova/resources/users/') . '/' . $user->id}}">Edit User</a>
                </li>
            @endif
    </ul>
</div>