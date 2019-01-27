<section class="work">
    <div class="work__inner">

        <h1>{{$block->title}}</h1>
        <div class="work__image">
            @if($block->img !== null)
                <img src="/storage/{{$block->img}}" alt="how does it works">
            @endif
        </div>

    </div>
</section>

