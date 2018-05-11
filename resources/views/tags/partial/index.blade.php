<p class="lead"><i class="fa fa-tags"></i> 태그</p>

<ul class="list-unstyled">
  @foreach($allTags as $tag)
    <li class="{{ str_contains(request()->path(), $tag->slug) ? 'active' : '' }}">
      <a href="{{ route('tags.articles.index', $tag->slug) }}">
        {{ $tag->name }}
        @if($count = $tag->articles->count())
          <span class="badge badge-info">{{ $count }}</span>
        @endif
      </a>
    </li>
  @endforeach
</ul>