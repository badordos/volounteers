@extends('layouts.dm')
@section('content')

    <div class="main">

        <transition name="fade">
            <section class="popup popup--form" v-show="activeHide" @click="closeHidePopup">
                <div class="popup__inner">

                    <h1>Hide Campaign</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi aperiam aut deserunt dicta
                        dolorum eos est excepturi facere fuga hic incidunt libero natus nemo quae quam quidem quos
                        similique, suscipit.</p>

                    <form method="POST"
                          action="{{route('campaign.hide', ['campaign'=>$campaign,'user'=> auth()->user()])}}">
                        {{csrf_field()}}
                        <textarea name="text_reason" cols="30" rows="10" :maxlength="lengthOfPopupTextArea"></textarea>
                        <input type="submit" class="btn btn--bold" value="Send">
                    </form>

                    <button type="button" class="btn btn--close popup__close" @click="closeHide"></button>
                </div>
            </section>
        </transition>

        <transition name="fade">
            <section class="popup popup--form" v-if="activeJoinPopup" @click="closeJoinPopup">
                <div class="popup__inner">

                    <h1>Please, join to campaign</h1>

                    <a href="{{route('campaign.join', ['campaign' => $campaign, 'user' => auth()->user()])}}" class="btn btn--bold">Join now</a>

                    <button type="button" class="btn btn--close popup__close" @click="closeJoin"></button>
                </div>
            </section>
        </transition>

        <section class="single-campaign">
            <div class="single-campaign__inner">

                <photo-gallery>
                    @if(isset($media_main))
                        <gallery-item preview="{{ $media_main->getUrl() }}" :selected="true"></gallery-item>
                    @elseif(isset($video))
                        <gallery-item preview="{{$src = $video_preview ? $video_preview->getUrl() : ''}}" type="video" src-video="{{$video->getUrl()}}" type-video="{{$video->mime_type}}" :selected="true"></gallery-item>

                    @endif
                    @if(isset($media))
                        @foreach($media as $image)
                            <gallery-item preview="{{ $image->getUrl() }}"></gallery-item>
                        @endforeach
                    @endif
                </photo-gallery>

                <div class="single-campaign__column">
                    {{--campaign's creator--}}
                    <auth-user avatar="
                                @if(isset($campaign->user->image))
                                    /storage/{{ $campaign->user->image }}
                                @else
                                    {{ asset('images/robot1.svg') }}
                                @endif
                                "
                               nick-name="{{$campaign->user->name}}"
                               :note-of-achievements="{{$campaign->user->achievmentsArray()}}"
                               @if(isset($campaign->city))
                                    location="{{$campaign->city->title}}"
                               @elseif(isset($campaign->worldwide))
                                    location="Worldwide"
                               @endif
                               link="{{route('public-profile', $campaign->user)}}"
                               >
                    </auth-user>

                    <h1 class="single-campaign__title">{{$campaign->title}}</h1>
                    <p class="single-campaign__description">
                        {!! nl2br(e($campaign->description))!!}
                    </p>

                    <div class="statistics">
                        <div class="statistics__item">
                            <svg>
                                <use xlink:href="#icon-human" />
                            </svg>
                            <p>
                                {{$volunteers}} volonteers | {{$percent = $campaign->volunteers_needed != 0 ? $percent . '%' : ''}}
                            </p>
                        </div>
                        <div class="statistics__item">
                            <p>
                                <b>Needed {{$campaign->volunteers_needed}} volunteers</b>
                            </p>
                        </div>
                        {{--<div class="statistics__item">--}}
                            {{--<svg>--}}
                                {{--<use xlink:href="#icon-coin" />--}}
                            {{--</svg>--}}
                            {{--<p>--}}
                                {{--9456 coins | 48%--}}
                            {{--</p>--}}
                        {{--</div>--}}
                        {{--<div class="statistics__item">--}}
                            {{--<p>--}}
                                {{--<b>Needed 15000 coins</b>--}}
                            {{--</p>--}}
                        {{--</div>--}}
                    </div>

                    {{--Проверка нажатия пользователем кнопок Join & Hide--}}
                    @if (auth()->check() && $campaign->joinedUsers->contains(auth()->user()))
                        <button type="button" class="btn btn--bold disabled">Joined</button>
                    @elseif($campaign->readiness !== 'success')
                        <button type="button" class="btn btn--bold disabled">Join Now</button>
                    @else
                        <a href="{{route('campaign.join', ['campaign' => $campaign, 'user' => auth()->user()])}}" class="btn btn--bold">Join now</a>
                    @endif

                    @if (auth()->check() && $campaign->hidUsers->contains(auth()->user()))
                        <button type="button" class="btn btn--bold disabled">Hide</button>
                    @elseif($campaign->readiness !== 'success')
                        <button type="button" class="btn btn--bold disabled">Hide</button>
                    @else
                        <a href="#!" class="btn btn--bold" @click="openHide">Hide</a>
                    @endif

                </div>

            </div>
        </section>

        <tabs>
            <tab name="Campaign" :selected="true">
                @if(isset($steps))
                        <accordion-steps>
                                @foreach ($steps as $step)
                                <form action="{{route('activeStep', $step)}}" method="POST" class="accordion__form">
                                    {{csrf_field()}}
                                    <accordion-step title="{{$step->title}}"
                                                    @if($step->active == true)
                                                        active-step="true"
                                                        :selected="true"
                                                    @endif
                                                    {{--Если пользователь может добавить голосование в шаг--}}
                                                    @if( optional(auth()->user())->can('createVotingInStep',[$step, $steps]) && auth()->user()->can('author',[$campaign]) && $campaign->readiness == 'success')
                                                        save-route="{{route('addVoting', $campaign)}}"
                                                        show-add-vote="true"
                                                        toggle-id="{{$step->id}}"
                                                    @endif
                                                    {{--Если пользователь может переключать шаги показываем переключатель--}}
                                                    @if(optional(auth()->user())->can('author',[$campaign]))
                                                        :disabled-toggle="false"
                                                    @else
                                                        :disabled-toggle="true"
                                                    @endif >
                                        <p>{{$step->description}}</p>
                                        @if(isset($step->voting))
                                            <vote {{--Выводить попап join--}}
                                                  @if ($campaign->joinPopup($step))
                                                        :show-join="true"
                                                  @endif

                                                  {{--Если пользователь голосовал--}}
                                                  @if ($step->voting->users->contains(auth()->user()))
                                                        :voted="true"
                                                        :result-active="true"
                                                        :active-variant="{{auth()->user()->votingVariant($step->voting)}}"
                                                  @endif

                                                  {{--Если пользователь не авторизован, или не присоединился, или голосовал, или шаг не активен, или кампания не одобрена - не даем голосовать--}}
                                                  @if (! auth()->check() || ! $campaign->joinedUsers->contains(auth()->user())
                                                    || auth()->user()->votings->contains($step->voting) || $step->active == false || $campaign->readiness != 'success')
                                                        :disabled="true"
                                                  @else
                                                        :disabled="false"
                                                        route="{{route('voting', ['voting_id' => $step->voting->id, 'user_id' => Auth::user()->id ])}}"
                                                        token="{{csrf_token()}}"
                                                  @endif

                                                  {{-- Если пользователь - создатель кампании и шаг не активен и шаг ниже активного = разрешаем удалить --}}
                                                  @if(optional(auth()->user())->can('author',[$campaign])
                                                        && $step->active != true && $step->lowerStep($steps, $step) == false)
                                                  delete-route="{{route('deleteVoting', $campaign)}}"
                                                  :active-delete-button="true"
                                                  :delete-id="{{$step->voting->id}}"
                                                  @endif
                                            >

                                                <template slot="title">
                                                    {{$step->voting->title}}
                                                </template>

                                                <template slot="description">
                                                    <p>{{$step->voting->description}}</p>
                                                </template>

                                                <template slot="variants">
                                                    @foreach(unserialize($step->voting->variants) as $key => $variant)
                                                        <vote-variant :value="{{$key}}" :count-of-voting="{{$step->voting->users()->where('variant', $key)->count()}}">
                                                            {{$variant['title']}}
                                                        </vote-variant>
                                                    @endforeach
                                                </template>
                                            </vote>

                                        @endif
                                    </accordion-step>
                                </form>
                                @endforeach
                        </accordion-steps>
                @endif

                    {{--<accordion-step title="Order garbage trucks" :selected="true">--}}
                        {{--<p>Research is mutual. Descussion is a turbulent tachet. Degradation regionally oxidizes the--}}
                            {{--bio-mineral mineral, and this process can be repeated many times. Without questioning the--}}
                            {{--possibility of different approaches to soil, overmoistening accelerates the drying cabinet.--}}
                            {{--Loess cools the glue equally in all directions. In laboratory conditions, it was established--}}
                            {{--that the weathering crust adsorbs the rinse hygrometer.</p>--}}
                        {{--<p>However, as the sample is increased, the stump is elastically carried by an acidic--}}
                            {{--waterproof, regardless of the predictions of the theoretical model of the phenomenon. As--}}
                            {{--practice of regime observations in the field shows, the soil crust is expertly verifiable.--}}
                            {{--Waxing causes a colloid.</p>--}}

                        {{--<vote :disabled="false" route="" token="">--}}
                            {{--<template slot="title">--}}
                                {{--Which shovels best buy?--}}
                            {{--</template>--}}
                            {{--<template slot="description">--}}
                                {{--<p>Research is mutual. Descussion is a turbulent tachet. Degradation regionally oxidizes--}}
                                    {{--the bio-mineral mineral, and this process can be repeated many times. Without--}}
                                    {{--questioning the possibility of different approaches to soil, overmoistening--}}
                                    {{--accelerates the drying cabinet. Loess cools the glue equally in all directions. In--}}
                                    {{--laboratory conditions, it was established that the weathering crust adsorbs the--}}
                                    {{--rinse hygrometer.</p>--}}

                                {{--<p>However, as the sample is increased, the stump is elastically carried by an acidic--}}
                                    {{--waterproof, regardless of the predictions of the theoretical model of the--}}
                                    {{--phenomenon. As practice of regime observations in the field shows, the soil crust is--}}
                                    {{--expertly verifiable. Waxing causes a colloid.</p>--}}

                            {{--</template>--}}

                            {{--<template slot="variants">--}}
                                {{--<vote-variant :value="1" :count-of-votes="10">--}}
                                    {{--Take part in campaign--}}
                                {{--</vote-variant>--}}
                                {{--<vote-variant :value="2" :count-of-votes="15">--}}
                                    {{--Take part in campaign--}}
                                {{--</vote-variant>--}}
                                {{--<vote-variant :value="3" :count-of-votes="20">--}}
                                    {{--Take part in campaign--}}
                                {{--</vote-variant>--}}
                                {{--<vote-variant :value="4" :count-of-votes="25">--}}
                                    {{--Take part in campaign--}}
                                {{--</vote-variant>--}}
                            {{--</template>--}}
                        {{--</vote>--}}
                        {{--<chat title="Chat" :count-of-comments="2">--}}
                            {{--<comment :status-expert="true">--}}
                                {{--<template slot="user">--}}
                                    {{--<auth-user avatar="{{ asset('images/team1.png') }}"--}}
                                               {{--nick-name="benefactor_01"--}}
                                               {{--:note-of-achievements="['Best volonteer of september', 'achievement 2', 'achievement 3', 'achievement 4']">--}}
                                    {{--</auth-user>--}}
                                {{--</template>--}}
                                {{--<template slot="comment">--}}
                                    {{--<p>Research is mutual. Descussion is a turbulent tachet. Degradation regionally--}}
                                        {{--oxidizes the bio-mineral mineral, and this process can be repeated many times.--}}
                                        {{--Without questioning the possibility of different approaches to soil,--}}
                                        {{--overmoistening accelerates the drying cabinet. Loess cools the glue equally in--}}
                                        {{--all directions. In laboratory conditions, it was established that the weathering--}}
                                        {{--crust adsorbs the rinse hygrometer.</p>--}}

                                    {{--<p>However, as the sample is increased, the stump is elastically carried by an--}}
                                        {{--acidic waterproof, regardless of the predictions of the theoretical model of the--}}
                                        {{--phenomenon. As practice of regime observations in the field shows, the soil--}}
                                        {{--crust is expertly verifiable. Waxing causes a colloid.</p>--}}

                                {{--</template>--}}
                            {{--</comment>--}}
                            {{--<comment>--}}
                                {{--<template slot="user">--}}
                                    {{--<auth-user avatar="{{ asset('images/team1.png') }}"--}}
                                               {{--nick-name="benefactor_01"--}}
                                               {{--:note-of-achievements="['Best volonteer of september', 'achievement 2', 'achievement 3', 'achievement 4']">--}}
                                    {{--</auth-user>--}}
                                {{--</template>--}}
                                {{--<template slot="comment">--}}
                                    {{--<p>Research is mutual. Descussion is a turbulent tachet. Degradation regionally--}}
                                        {{--oxidizes the bio-mineral mineral, and this process can be repeated many times.--}}
                                        {{--Without questioning the possibility of different approaches to soil,--}}
                                        {{--overmoistening accelerates the drying cabinet. Loess cools the glue equally in--}}
                                        {{--all directions. In laboratory conditions, it was established that the weathering--}}
                                        {{--crust adsorbs the rinse hygrometer.</p>--}}

                                    {{--<p>However, as the sample is increased, the stump is elastically carried by an--}}
                                        {{--acidic waterproof, regardless of the predictions of the theoretical model of the--}}
                                        {{--phenomenon. As practice of regime observations in the field shows, the soil--}}
                                        {{--crust is expertly verifiable. Waxing causes a colloid.</p>--}}

                                {{--</template>--}}
                            {{--</comment>--}}
                            {{--<comment>--}}
                                {{--<template slot="user">--}}
                                    {{--<auth-user avatar="{{ asset('images/team1.png') }}"--}}
                                               {{--nick-name="benefactor_01"--}}
                                               {{--:note-of-achievements="['Best volonteer of september', 'achievement 2', 'achievement 3', 'achievement 4']">--}}
                                    {{--</auth-user>--}}
                                {{--</template>--}}
                                {{--<template slot="comment">--}}
                                    {{--<p>Research is mutual. Descussion is a turbulent tachet. Degradation regionally--}}
                                        {{--oxidizes the bio-mineral mineral, and this process can be repeated many times.--}}
                                        {{--Without questioning the possibility of different approaches to soil,--}}
                                        {{--overmoistening accelerates the drying cabinet. Loess cools the glue equally in--}}
                                        {{--all directions. In laboratory conditions, it was established that the weathering--}}
                                        {{--crust adsorbs the rinse hygrometer.</p>--}}

                                    {{--<p>However, as the sample is increased, the stump is elastically carried by an--}}
                                        {{--acidic waterproof, regardless of the predictions of the theoretical model of the--}}
                                        {{--phenomenon. As practice of regime observations in the field shows, the soil--}}
                                        {{--crust is expertly verifiable. Waxing causes a colloid.</p>--}}

                                {{--</template>--}}
                            {{--</comment>--}}
                            {{--<comment>--}}
                                {{--<template slot="user">--}}
                                    {{--<auth-user avatar="{{ asset('images/team1.png') }}"--}}
                                               {{--nick-name="benefactor_01"--}}
                                               {{--:note-of-achievements="['Best volonteer of september', 'achievement 2', 'achievement 3', 'achievement 4']">--}}
                                    {{--</auth-user>--}}
                                {{--</template>--}}
                                {{--<template slot="comment">--}}
                                    {{--<p>Research is mutual. Descussion is a turbulent tachet. Degradation regionally--}}
                                        {{--oxidizes the bio-mineral mineral, and this process can be repeated many times.--}}
                                        {{--Without questioning the possibility of different approaches to soil,--}}
                                        {{--overmoistening accelerates the drying cabinet. Loess cools the glue equally in--}}
                                        {{--all directions. In laboratory conditions, it was established that the weathering--}}
                                        {{--crust adsorbs the rinse hygrometer.</p>--}}

                                    {{--<p>However, as the sample is increased, the stump is elastically carried by an--}}
                                        {{--acidic waterproof, regardless of the predictions of the theoretical model of the--}}
                                        {{--phenomenon. As practice of regime observations in the field shows, the soil--}}
                                        {{--crust is expertly verifiable. Waxing causes a colloid.</p>--}}

                                {{--</template>--}}
                            {{--</comment>--}}

                            {{--<template slot="current-user">--}}
                                {{--<comment :show="true">--}}
                                    {{--<template slot="user">--}}
                                        {{--<auth-user avatar="{{ asset('images/team1.png') }}"--}}
                                                   {{--@if(Auth::user())--}}
                                                   {{--nick-name="{{Auth::user()->name}}"--}}
                                                   {{--@endif--}}
                                                   {{--:note-of-achievements="['Best volonteer of september', 'achievement 2', 'achievement 3', 'achievement 4']">--}}
                                        {{--</auth-user>--}}
                                    {{--</template>--}}
                                    {{--<template slot="comment">--}}
                                        {{--<textarea name="comment" id="" cols="30" rows="10"></textarea>--}}
                                    {{--</template>--}}
                                {{--</comment>--}}
                            {{--</template>--}}

                        {{--</chat>--}}
                    {{--</accordion-step>--}}

                    {{--<template slot="announcement">--}}
                        {{--<section class="announcement">--}}
                            {{--<h1>Announcement</h1>--}}

                            {{--<div class="announcement__item">--}}
                                {{--<h3>Order garbage trucks</h3>--}}
                                {{--<p>Research is mutual. Descussion is a turbulent tachet. Degradation regionally oxidizes--}}
                                    {{--the bio-mineral mineral, and this process can be repeated many times. Without--}}
                                    {{--questioning the possibility of different approaches to soil, overmoistening--}}
                                    {{--accelerates the drying cabinet. Loess cools the glue equally in all directions. In--}}
                                    {{--laboratory conditions, it was established that the weathering crust adsorbs the--}}
                                    {{--rinse hygrometer.</p>--}}

                                {{--<p>However, as the sample is increased, the stump is elastically carried by an acidic--}}
                                    {{--waterproof, regardless of the predictions of the theoretical model of the--}}
                                    {{--phenomenon. As practice of regime observations in the field shows, the soil crust is--}}
                                    {{--expertly verifiable. Waxing causes a colloid.</p>--}}

                                {{--<span class="announcement__date">05.07.2018</span>--}}
                            {{--</div>--}}
                            {{--<div class="announcement__item">--}}
                                {{--<h3>Order garbage trucks</h3>--}}
                                {{--<p>Research is mutual. Descussion is a turbulent tachet. Degradation regionally oxidizes--}}
                                    {{--the bio-mineral mineral, and this process can be repeated many times. Without--}}
                                    {{--questioning the possibility of different approaches to soil, overmoistening--}}
                                    {{--accelerates the drying cabinet. Loess cools the glue equally in all directions. In--}}
                                    {{--laboratory conditions, it was established that the weathering crust adsorbs the--}}
                                    {{--rinse hygrometer.</p>--}}

                                {{--<p>However, as the sample is increased, the stump is elastically carried by an acidic--}}
                                    {{--waterproof, regardless of the predictions of the theoretical model of the--}}
                                    {{--phenomenon. As practice of regime observations in the field shows, the soil crust is--}}
                                    {{--expertly verifiable. Waxing causes a colloid.</p>--}}

                                {{--<span class="announcement__date">05.07.2018</span>--}}
                            {{--</div>--}}

                            {{--<a href="#!" class="btn btn--bold">More news</a>--}}
                        {{--</section>--}}
                    {{--</template>--}}

            </tab>
            <tab name="About">
                <section class="section-about">
                    <h1>About</h1>
                    <section class="section-about__item">
                        {!! $campaign->about_desc !!}
                    </section>
                </section>
            </tab>

            @include('parts.campaign.news')

        </tabs>

    </div>


@endsection