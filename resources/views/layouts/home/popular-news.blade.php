<div class="popular-news">
  <h5 class="mb-2 header">
    <i class="fas fa-newspaper fa-sm mr-1"></i>
    Popular News
  </h5>

  @foreach ($popularNews as $news)
    <div class="item d-flex justify-content-between my-2">
      {{-- Content --}}
      <div>
        <a
          href="{{ route('show_news', ['newsId' => $news->id, 'title' => preg_replace('/[^a-z0-9]+/', '-', strtolower($news->title))]) }}">
          <h6 class="mb-0">{{ ucwords($news->title) }}</h6>
        </a>
        <small class="text-muted">
          <i class="far fa-clock"></i>
          {{ $news->created_at->diffForHumans() }}
        </small>
      </div>

      {{-- Banner --}}
      <img src="{{ $news->getBannerImageUrl() }}" alt="" class="banner">
    </div>
  @endforeach

</div>
