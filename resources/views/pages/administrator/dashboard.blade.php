@extends('layouts.default.layout')

@push('css')
  <style>
    #dashboard-page .fa-spin.loading-indicator {
      font-size: 25px;
      display: block !important;
    }

    #dashboard-page .chart .card-header {
      min-height: auto;
    }

    #dashboard-page .chart .card-body {
      min-height: 150px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

  </style>
@endpush

@section('content')
  <div id="dashboard-page">
    {{-- First Row --}}
    @include('components.dashboard.quick-infos', [
    'title' => 'Dashboard',
    'totalAdmin' => $totalAdmin,
    'totalAuthor' => $totalAuthor,
    'totalNews' => $totalNews,
    'totalTags' => $totalTags,
    ])

    {{-- Second Row --}}
    <div class="row">
      {{-- Tags Chart --}}
      <div class="col-12 col-md-6">
        @include('components.dashboard.popular-tags-chart')
      </div>

      {{-- News Chart --}}
      <div class="col-12 col-md-6">
        @include('components.dashboard.trending-news-chart')
      </div>
    </div>
  </div>
@endsection
