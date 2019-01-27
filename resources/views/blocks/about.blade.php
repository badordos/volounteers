<section class="about__item">
    <div class="about__item-image about__item-image--center">
        @if($block->img !== null)
            <img src="/storage/{{$block->img}}" alt="robot">
        @endif
    </div>
    <div class="about__item-content">
        {!!$block->content!!}
    </div>
</section>