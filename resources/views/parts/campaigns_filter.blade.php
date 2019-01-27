<app-filter :variants="['Prague', 'Moscow', 'Paris', 'Rome', 'New York', 'London']"
            :flags="['Education', 'Children', 'Nature', 'Health', 'Science']"
            :settings="['{{ $flags }}', '{{ $verification }}']"
            @if(isset($disabledBtnFilter)) :disabled-btn-filter="{{ $disabledBtnFilter }}" @endif>
    <h1 slot="title">{{ $title  }}</h1>

    @if(isset($createCampaign))
        <template slot="create-campaign">
            <a href="{{route('create-campaign-step-1')}}" class="btn btn--bold reverse filter--create">Create
                campaign</a>
        </template>
    @endif
</app-filter>

