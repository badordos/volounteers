<section class="create">
    <div class="create__inner">

        <h1>Create</h1>

        <div class="create__image">
            <img src="/storage/{{$createCampaign->img}}" alt="robot">
        </div>
        <div class="create__list">
            {!! $createCampaign->content !!}

            <a href="{{ route('create-campaign-step-1') }}" class="btn">{{$createCampaign->title}}</a>
        </div>
    </div>
</section>