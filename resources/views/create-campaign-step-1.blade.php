@extends('layouts.dm')
@section('content')

    <div class="main">
        <section class="create-campaign">
            <div class="create-campaign__inner">

                <div class="create-campaign__inner-photo">
                    <img src="{{ asset('images/robot3.svg') }}" alt="robot">
                </div>
                <div class="create-campaign__content">
                    <div class="create-campaign__inner-header">
                        <b class="create-campaign__step">
                            Step
                            <span class="create-campaign__step-current">1</span>
                            <span class="create-campaign__step-next">5</span>
                        </b>
                        <h1>General information</h1>
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

                    <div class="create-campaign__form">
                        <form action="{{route('store-campaign-step-1')}}" method="POST">
                            <div class="create-campaign__form-column create-campaign__form-column--wide">

                                @if(isset($campaign))
                                    <input name="campaign_id" type="hidden" value="{{$campaign->id}}">
                                @endif

                                {{csrf_field()}}
                                <label class="label">
                                    <h3>Campaign name*</h3>
                                    <transition name="fade">
                                        <span class="create-steps__error-limit" v-show="nameLimit">Words limit reached</span>
                                    </transition>
                                    <input name="title" type="text" value="@if(isset($campaign)){{trim($campaign->title)}}@else{{trim(old('title'))}}@endif" required ref="titleCampaign"  maxlength="50" autofocus autocomplete="off" @input="maxLengthObserverName" :class="{'complete' : nameLimit}">
                                </label>
                                <label class="label">
                                    <h3>
                                        Campaign description <br>
                                        ( max 695 characters )
                                    </h3>
                                    <transition name="fade">
                                        <span class="create-steps__error-limit" v-show="descriptionLimit">Words limit reached</span>
                                    </transition>
                                    <div class="scroll" :class="{'complete' : descriptionLimit}">
                                        <textarea name="description" maxlength="695" autocomplete="off" ref="descriptionCampaign" ref="descriptionCampaign" @input="maxLengthObserverDescription">@if(isset($campaign)){{$campaign->description}}@else{{old('description')}}@endif</textarea>
                                    </div>
                                </label>
                            </div>
                            <div class="create-campaign__form-column">
                                <span class="label select">
                                    <h3>Campaign direction</h3>
                                    <v-select
                                        :options={{$categories}}
                                        ref="directionCampaign"
                                        :value="getLabelDirection({{$value = isset($campaign) ? $campaign->category_id : ''}})">
                                    </v-select>
                                </span>
                                <span class="label select">
                                    <h3 class="row">Location
                                        <label class="checkbox-container">
                                            <input type="checkbox" v-model="worldFilterCreate" @click="acceptWorldWideFilter" name="worldwide"
                                                   {{$value = isset($campaign) && $campaign->worldwide == true ? 'checked="checked"' : ''}}>
                                            <span class="checkbox"></span>
                                            Worldwide
                                        </label>
                                    </h3>
                                    <v-select :options="{{$cities}}"
                                              :class="{ 'disabled': worldFilterCreate }"
                                              :disabled="worldFilterCreate" ref="select"
                                              :value="getLabelLocation({{$value = isset($campaign) ? $campaign->city_id : ''}})">
                                    </v-select>
                                </span>
                                <input type="text" class="visually-hidden" style="display: none;" name="category_id" ref="category_id">
                                <input type="text" class="visually-hidden" style="display: none;" name="city_id" ref="city_id">
                                <label class="label label--human">
                                    <h3>
                                        Needed volonteers
                                    </h3>
                                    <input name="volunteers_needed" type="number" value="{{$volunteers = isset($campaign) ? $campaign->volunteers_needed : old('volunteers_needed')}}" required min="0" max="1000000" ref="volonteersCampaign">
                                </label>
                                {{--<label class="label label--coin">--}}
                                    {{--<h3>--}}
                                        {{--Nedeed coins--}}
                                    {{--</h3>--}}
                                    {{--<input name="campaign-name" type="number" required min="0" max="10000000">--}}
                                {{--</label>--}}

                                <button type="submit" class="btn btn--big reverse btn--arrow-right btn--step" @click="validateCreateStep1">
                                    Next step
                                    <span class="arrow"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </section>
    </div>

@endsection