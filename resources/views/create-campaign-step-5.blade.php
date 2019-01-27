@extends('layouts.dm')
@section('content')

    <div class="main">
        <form action="{{route('store-campaign-step-5')}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
            <transition name="fade">
                <section class="popup popup--form" v-show="activeCreateCampaignPopup" @click="closeCreateCampaignPopup">
                    <div class="popup__inner">

                        <h1>Create campaign</h1>
                        <p>Please check the campaign information. After sending data for moderation, <span class="accent">it will be impossible to change the content.</span></p>

                        <ul class="popup__buttons">
                            <li class="popup__buttons-button">
                                <a target="_blank" href="{{route('preview')}}" class="btn reverse">
                                    Preview campaign
                                </a>
                            </li>


                            <li class="popup__buttons-button">
                                <button type="submit" name="creation_complete" value="true" href="{{ route('creationComplete') }}" class="btn reverse">
                                    Create campaign
                                </button>
                            </li>

                        </ul>

                        <button type="button" class="btn btn--close popup__close" @click="closeCreateCampaignPopupBtn"></button>
                    </div>
                </section>
            </transition>

            <section class="create-campaign">
                <div class="create-campaign__inner">

                    <div class="create-campaign__inner-photo">
                        <img src="{{ asset('images/robot3.svg') }}" alt="robot">
                    </div>

                    <div class="create-campaign__content create-campaign__content--column">
                        <div class="create-campaign__inner-header">
                            <button type="submit" name="back_step_5" value="true" class="btn btn--bold btn--arrow-left">Back <span class="arrow"></span></button>
                            <b class="create-campaign__step">
                                Step
                                <span class="create-campaign__step-current">5</span>
                                <span class="create-campaign__step-next">5</span>
                            </b>
                            {{--<h1>Tell friends</h1>--}}
                        </div>

                        <div class="create-campaign__content--center">

                            <create-campaign-card preload-image="
                                @if($image !== null)
                            {{$image->getUrl()}}
                            @endif">
                                <h1 slot="title">{{$campaign->title}}</h1>
                                <template slot="content">
                                    <p>{!! nl2br(e($campaign->description))!!}</p>

                                    <div class="statistics create-campaign-card__statistics">
                                        <div class="statistics__item">
                                            <svg>
                                                <use xlink:href="#icon-human" />
                                            </svg>
                                            <p>
                                                {{$campaign->volunteers_needed}} volonteers needed
                                            </p>
                                        </div>
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach($errors->all() as $error)
                                                        <li>{{$error}}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        {{--<div class="statistics__item">--}}
                                        {{--<svg>--}}
                                        {{--<use xlink:href="#icon-coin" />--}}
                                        {{--</svg>--}}
                                        {{--<p>--}}
                                        {{--9456 coins needed--}}
                                        {{--</p>--}}
                                        {{--</div>--}}
                                    </div>
                                </template>
                            </create-campaign-card>
                            <ul class="create-campaign__buttons">
                                <li class="create-campaign__buttons-button">
                                    <a target="_blank" href="{{route('preview')}}" class="btn btn--big reverse">
                                        Preview campaign
                                    </a>
                                </li>
                                <li class="create-campaign__buttons-button">
                                    <a href="{{route('creationComplete')}}" class="btn btn--big reverse" @click.prevent="openCreateCampaignPopup">
                                        Create campaign
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>

                </div>
            </section>
        </form>
    </div>
@endsection