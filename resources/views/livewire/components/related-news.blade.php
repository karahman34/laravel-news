<div>
  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Related News</h5>

    <a href="{{ route('search') }}?tags={{ $news->tags->pluck('name')->join(',') }}" class="btn btn-primary py-1">SEE
      MORE</a>
  </div>

  <div class="card mt-2">
    <div class="card-body">
      <div class="row">
        @foreach ($relatedNews as $news)
          <div class="col-6 col-md-3 px-1">
            <a href="{{ route('show_news', ['news' => $news->id, 'title' => preg_replace('/[^a-z0-9]+/', '-', strtolower($news->title))]) }}"
              class="d-block mb-0">
              {{-- Banner --}}
              <img src="{{ $news->getBannerImageUrl() }}" alt="" class="img-fluid banner">
              {{-- Title --}}
              <small class="d-block">{{ ucwords($news->title) }}</small>
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </div>

</div>
