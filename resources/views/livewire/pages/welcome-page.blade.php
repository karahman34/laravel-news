<div>
  {{-- Headline News --}}
  @include('components.news.headline-news', ['headlineNews' => $headlineNews])

  <br>

  {{-- List of News --}}
  <div wire:loading.remove>
    @foreach ($news as $item)
      @include('components.news.news-feed', ['news' => $item])
    @endforeach
  </div>

  {{-- Loading --}}
  @include('components.home.loading')

  {{-- Pagination --}}
  @include('components.home.pagination', ['data' => $news])
</div>
