@extends('layouts.dm')
@section('content')

    @include('parts.personal_menu')

    <div class="main
            @if(Route::currentRouteName() == 'profile.campaigns')
            personal-page
            @endif ">
        <div class="main__inner">

            <about-campaigns :hide-paddings="true" id="scrollCreated">
                <template slot="filter">
                    @include('parts.campaigns_filter', ['flags' => 'filter', 'title' => 'Created campaigns', 'verification' => '', 'createCampaign' => true, 'disabledBtnFilter' => 'true'])
                </template>

                <template slot="campaigns">
                    @forelse($createdCampaigns as $campaign)
                        <campaign-card-step
                                @if($campaign->readiness === 'success')
                                    step="{{$campaign->numberOfActiveStep($campaign->steps)}}"
                                    link-view="{{route('single-campaign', $campaign)}}"
                                @elseif($campaign->readiness === 'moderation')
                                    step="Moderation"
                                    link-view="{{route('single-campaign', $campaign)}}"
                                @else
                                    step="In progress"
                                    :not-verified="true"
                                    link-view="{{route('create-campaign-step-1')}}"
                                @endif
                                image=""
                                >
                            <h3 slot="title">{{str_limit($campaign->title, $limit = 45, $end = '...')}}</h3>

                            <div class="campaign-card__top">
                            {{--<span class="campaign-card__top-icon">--}}
                                {{--<svg>--}}
                                    {{--<use xlink:href="#icon-coin"/>--}}
                                {{--</svg>--}}
                            {{--</span>--}}
                                {{--<span class="campaign-card__top-text">--}}
                                        {{--9 456 coins | 48%--}}
                                {{--</span>--}}
                                <span class="campaign-card__verification
                                @if($campaign->readiness === 'success')
                                    verified
                                @else
                                    not-verified
                                @endif
                                ">
                                <span class="verified">Verified</span>
                                <span class="not-verified">Not verified</span>
                            </span>
                            </div>
                        </campaign-card-step>
                    @empty
                        <p>No campaigns</p>
                    @endforelse
                </template>
            </about-campaigns>

            <about-campaigns :hide-paddings="true" id="scrollToTake">
                <template slot="filter">
                    @include('parts.campaigns_filter', ['flags' => 'filter', 'title' => 'Campaigns to take part', 'verification' => '', 'disabledBtnFilter' => 'true'])
                </template>

                <template slot="campaigns">
                    @forelse($CampaignsToTakePart as $campaign)
                        <campaign-card-step step="{{$campaign->numberOfActiveStep($campaign->steps)}}" link-view="{{route('single-campaign', $campaign)}}">
                            <h3 slot="title">{{str_limit($campaign->title, $limit = 45, $end = '...')}}</h3>

                            <div class="campaign-card__top">
                                {{--<span class="campaign-card__top-icon">--}}
                                {{--<svg>--}}
                                {{--<use xlink:href="#icon-coin"/>--}}
                                {{--</svg>--}}
                                {{--</span>--}}
                                {{--<span class="campaign-card__top-text">--}}
                                {{--9 456 coins | 48%--}}
                                {{--</span>--}}
                                <span class="campaign-card__verification
                                @if($campaign->readiness === 'success')
                                        verified
                                @else
                                        not-verified
                                @endif
                                        ">
                                <span class="verified">Verified</span>
                                <span class="not-verified">Not verified</span>
                            </span>
                            </div>
                        </campaign-card-step>
                    @empty
                        <p>No campaigns</p>
                    @endforelse
                </template>
            </about-campaigns>
        </div>
    </div>
@endsection