<div class="show-news">
  {{-- News --}}
  <div class="card">
    <div class="card-body">
      {{-- Title --}}
      <div class="card-title">
        {{-- Title --}}
        <p class="title mb-2">{{ ucwords($news->title) }}</p>

        <div class="title-footer d-flex justify-content-between align-items-center">
          {{-- Created Time --}}
          <small class="created-time">
            {{ $news->created_at->format('D, d F Y H:i') }}
          </small>

          {{-- Share Buttons --}}
          <div class="d-flex align-items-center">
            <span class="text-muted mr-2">Share: </span>

            <div class="share-buttons a2a_kit a2a_kit_size_24 a2a_default_style">
              <a class="a2a_button_facebook"></a>
              <a class="a2a_button_twitter"></a>
              <a class="a2a_button_google_gmail"></a>
              <a class="a2a_button_copy_link"></a>
            </div>
          </div>
        </div>
      </div>

      <div class="dash-banner-divider"></div>

      {{-- Banner --}}
      <img src="{{ $news->getBannerImageUrl() }}" alt="" class="banner">

      {{-- Content --}}
      <div class="news-content mb-0 mt-3 mb-2">
        {!! $news->content !!}
      </div>

      {{-- Author --}}
      <div>
        <p class="mb-0" style="font-weight: 600">Author: </p>
        <span>{{ $news->author->name }}</span>
      </div>

      {{-- Tags --}}
      <div class="mt-1">
        <p class="mb-0" style="font-weight: 600">Tags: </p>
        @foreach ($news->tags as $tag)
          <a href="{{ route('search') }}?tags={{ $tag->name }}"
            class="btn btn-primary text-white news-tag py-0 my-1">{{ $tag->name }}</a>
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
      margin-bottom: 1rem;
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

    .show-news .card-title {
      margin-bottom: 0.25rem !important;
    }

    .show-news .banner {
      width: 100%;
      max-height: 400px;
      object-fit: cover;
    }

    .show-news .news-content img {
      max-width: 100%;
      max-height: 400px;
      object-fit: cover;
    }

    .share-buttons a:not(:last-child) {
      margin-right: 0.1rem;
    }

    @media screen and (max-width: 640px) {
      .show-news .card-title {
        margin-bottom: 0.5rem !important;
      }

      .show-news .title {
        text-align: center;
      }

      .show-news .title-footer {
        flex-direction: column;
      }

      .show-news .title-footer .created-time {
        margin-bottom: 0.25rem;
      }
    }

    @media screen and (min-width: 768px) {
      .show-news .title {
        font-size: 28px;
      }
    }

  </style>
@endpush

@push('script')
  <script src="https://static.addtoany.com/menu/page.js"></script>
  <script>
    $(document).ready(function() {
      // Increase views.
      $.post("{{ route('increase_view') }}", {
          news_id: "{{ $news->id }}",
          _token: $('meta[name=csrf-token]').attr('content')
        })
        .fail(() => {})

      // Set link for share buttons.
      const href = window.location.href
      $('.share-buttons a').each(function() {
        const anchor = $(this)
        anchor.attr('href', href)
      })
    })

  </script>
@endpush
