<div class="person">
    <div class="person__photo"
         @if($block->img !== null)
            style="background-image: url('/storage/{{$block->img}}');">
         @endif
    </div>
    <h2>{{$block->title}}</h2>
    {!!$block->content!!}
</div>