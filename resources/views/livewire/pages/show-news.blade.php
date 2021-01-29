<div class="show-news">
  {{-- News --}}
  <div class="card">
    <div class="card-body">
      {{-- Title --}}
      <div class="card-title mb-2">
        {{-- Title --}}
        <p class="title mb-0">{{ ucwords($news->title) }}</p>

        {{-- Created Time --}}
        <small class="created-time">
          {{ $news->created_at->format('D, d F Y H:i') }}
        </small>
      </div>

      <div class="dash-banner-divider"></div>

      {{-- Banner --}}
      <img src="{{ $news->getBannerImageUrl() }}" alt="" class="img-fluid">

      {{-- Body --}}
      <p class="mb-0 mt-3 mb-2">
        {!! $news->content !!}
      </p>

      {{-- Author --}}
      <div>
        <p class="mb-0" style="font-weight: 600">Author: </p>
        {{ $news->author->name }}
      </div>

      {{-- Tags --}}
      <div class="mt-1">
        <p class="mb-0" style="font-weight: 600">Tags: </p>
        @foreach ($news->tags as $tag)
          <a href="{{ route('search') }}?tags={{ $tag->name }}"
            class="btn btn-primary news-tag py-0 my-1">{{ $tag->name }}</a>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Related News --}}
  <div class="my-3">
    <livewire:components.related-news :news="$news"></livewire:components.related-news>
  </div>
</div>

@push('css')
  <style>
    .dash-banner-divider {
      width: 100%;
      height: 5px;
      background-color: rgb(211, 211, 211);
      margin-bottom: 17px;
    }

    .news-tag {
      border-radius: 4px !important;
    }

    .news-tag:not(:last-child) {
      margin-right: 4px;
    }

    .show-news .title {
      font-size: 21px;
      font-weight: 600;
    }

    .show-news .created-time {
      color: rgb(54, 54, 54);
    }

    @media screen and (min-width: 768px) {
      .show-news .title {
        font-size: 28px;
      }
    }

  </style>
@endpush

@push('script')
  <script>
    // Increase views
    $(document).ready(function() {
      $.post("{{ route('increase_view') }}", {
          news_id: "{{ $news->id }}",
          _token: $('meta[name=csrf-token]').attr('content')
        })
        .fail(() => {})
    })

  </script>
@endpush
