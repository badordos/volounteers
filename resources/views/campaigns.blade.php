@extends('layouts.dm')
@section('content')

    <div class="main">
        <div class="main__inner">
            {{--@include('parts.campaigns_filter', ['flags' => 'row', 'title' => 'Campaigns', 'verification' => 'verification'])--}}


            <campaigns-list text-btn="More campaigns">
                @foreach ($campaigns as $campaign)
                    <campaign-card title="{{$campaign->title}}"
                                   description="{{str_limit($campaign->description, $limit = 170, $end = '...')}}"
                                   image="{{$src = $campaign->getFirstMedia('preview_image') ? $campaign->getFirstMedia('preview_image')->getUrl('small') : ''}}"
                                   link="{{route('single-campaign', $campaign)}}"
                                   @if($campaign->joinedUsers->contains(auth()->user()))
                                        text-link="Joined"
                                   @else
                                        text-link="Read more"
                                   @endif
                    >
                        <div class="campaign-card__top">
                                    <span class="campaign-card__top-icon">
                                        <svg>
                                            <use xlink:href="#icon-human"/>
                                        </svg>
                                    </span>
                            <span class="campaign-card__top-text">
                                        {{$volunteers = $campaign->joinedUsers->count()}} volunteers |
                                        {{$percent = $campaign->volunteers_needed != 0 ? round($volunteers / $campaign->volunteers_needed * 100, 2) . '%' : ''}}
                                    </span>
                            <span class="campaign-card__verification verified">
                                        <span class="verified">Verified</span>
                                        <span class="not-verified">Not verified</span>
                                    </span>
                        </div>
                    </campaign-card>
                @endforeach
            </campaigns-list>
        </div>
    </div>


@endsection