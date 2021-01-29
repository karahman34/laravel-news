<div>
  {{-- Headline News --}}
  @include('components.news.headline-news', ['headlineNews' => $headlineNews])

  <br>

  {{-- List of News --}}
  @foreach ($news as $item)
    @include('components.news.news-feed', ['news' => $item])
  @endforeach

  {{-- Pagination --}}
  <div class="d-flex justify-content-center mt-4">
    {{ $news->links() }}
  </div>
</div>
