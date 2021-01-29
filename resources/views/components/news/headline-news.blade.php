<div id="headlineNewsIndicator" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    @foreach ($headlineNews as $key => $item)
      <li data-target="#headlineNewsIndicator" data-slide-to="{{ $key }}"></li>
    @endforeach
  </ol>
  <div class="carousel-inner">
    @foreach ($headlineNews as $key => $item)
      <div class="carousel-item @if ($key===0) active @endif">
        <a
          href="{{ route('show_news', ['newsId' => $item->id, 'title' => preg_replace('/[^a-z0-9]+/', '-', strtolower($item->title))]) }}">
          <img src="{{ $item->getBannerImageUrl() }}" class="d-block w-100 banner"
            alt="{{ $item->getBannerImageUrl() }}">
          <div class="carousel-caption">
            {{-- Mobile --}}
            <div class="d-block d-md-none">
              <h6>{{ ucwords($item->title) }}</h6>
            </div>

            {{-- Desktop --}}
            <div class="d-none d-md-block">
              <h5>{{ ucwords($item->title) }}</h5>
              <p>{{ $item->summary }}</p>
            </div>
          </div>
        </a>
      </div>
    @endforeach
  </div>
  <a class="carousel-control-prev" href="#headlineNewsIndicator" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#headlineNewsIndicator" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
