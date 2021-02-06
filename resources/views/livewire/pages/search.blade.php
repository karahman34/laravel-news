<div id="search-page">
  <h5>Results for: "{{ $q }}"</h5>

  {{-- Filter Block --}}
  <div id="filter-block" class="d-flex align-items-center justify-content-between mb-3">
    {{-- Order & Tags --}}
    <div class="d-flex align-items-center">
      <div>
        {{-- Order --}}
        <select id="order" class="form-control flex-grow-0" wire:model="order">
          <option value="" selected disabled>Order</option>
          <option value="asc">Asc</option>
          <option value="desc">Desc</option>
        </select>
      </div>

      {{-- Tags --}}
      <div class="input-icon tags">
        <i class="fas fa-tags"></i>
        <input type="search" class="ml-2 form-control" placeholder="tags1,tags2" wire:model.debounce.400ms="tags">
      </div>
    </div>

    {{-- Search --}}
    <div id="search-block">
      <div class="input-icon">
        <i class="fas fa-search"></i>
        <input type="search" class="d-inline-block form-control" placeholder="Enter your keywords.."
          wire:model.debounce.400ms="q">
      </div>
    </div>
  </div>

  {{-- List of News --}}
  <div wire:loading.remove>
    @foreach ($news as $item)
      @include('components.news.news-feed', ['news' => $item])
    @endforeach
  </div>

  {{-- Loading --}}
  <div class="mt-4">
    @include('components.home.loading')
  </div>

  {{-- Empty Block --}}
  @if (count($news) === 0)
    <div wire:loading.remove>
      <div class="h4 text-center text-muted mt-4">
        News not found.
      </div>
    </div>
  @endif

  {{-- Pagination --}}
  @include('components.home.pagination', ['data' => $news])
</div>

@push('css')
  <style>
    /* Mobile */
    @media screen and (max-width: 768px) {
      #filter-block {
        flex-direction: column;
      }

      #filter-block #search-block {
        margin-top: 8px;
      }
    }

    #search-page .input-icon {
      position: relative;
    }

    #search-page .input-icon i {
      position: absolute;
      color: rgb(71, 71, 71);
      font-size: 15px;
      top: 13px;
      left: 12px;
    }

    #search-page .input-icon input {
      padding-left: 33px;
    }

    #search-page .input-icon.tags i {
      position: absolute;
      color: rgb(71, 71, 71);
      font-size: 15px;
      top: 13px;
      left: 18px;
    }

  </style>
@endpush
