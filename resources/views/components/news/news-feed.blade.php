<div class="card news-feed">
  <div class="card-body">
    <div class="d-flex justify-content-between">
      {{-- Content --}}
      <div>
        {{-- Title --}}
        <a href="{{ route('show_news', ['newsId' => $news->id, 'title' => preg_replace('/[^a-z0-9]+/', '-', strtolower($news->title))]) }}"
          class="title">
          {{ ucwords($news->title) }}
        </a>

        {{-- Summary --}}
        <p class="d-none d-md-block summary mb-0">{{ $news->summary }}</p>

        {{-- Bottom --}}
        <small class="text-muted time">
          {{-- Time --}}
          <div class="my-1">
            <i class="fas fa-clock"></i>
            {{ $news->created_at->diffForHumans() }}
          </div>

          {{-- Tags --}}
          <div class="tags-block">
            <b class="mr-1">Tags :</b>
            @foreach ($news->tags as $tag)
              <a href="{{ route('search') }}?tags={{ $tag->name }}" class="tag">{{ $tag->name }}</a>
            @endforeach
          </div>
        </small>
      </div>

      {{-- Banner --}}
      <div>
        <img src="{{ $news->getBannerImageUrl() }}" alt="" class="img-fluid banner">
      </div>
    </div>
  </div>
</div>
