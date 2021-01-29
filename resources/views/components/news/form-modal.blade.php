@php
$action = !isset($news) ? 'create' : 'update';
$url = $action === 'create' ? route('administrator.news.store') : route('administrator.news.update', ['news' =>
$news->id]);
$method = $action === 'create' ? 'POST' : 'PATCH';
$modalTitle = $action === 'create' ? 'Create News' : 'Edit ' . $news->title;
$btnType = $action === 'create' ? 'btn-primary' : 'btn-warning';
@endphp

<div id="news-form-modal" class="modal fade" role="dialog" aria-hidden="true" data-keyboard="false"
  data-backdrop="static">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalScrollableTitle">{{ $modalTitle }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pb-0">
        {{-- The Form --}}
        <form id="news-form" action="{{ $url }}" method="POST" enctype="multipart/form-data"
          class="need-ajax has-datatable has-modal" data-datatable="#news-datatable">
          @csrf @method($method)

          {{-- First Row --}}
          <div class="row">
            <div class="col-12 col-md-6">
              {{-- Title --}}
              <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Title"
                  value="{{ $news->title ?? '' }}" required autofocus>
              </div>
            </div>

            {{-- Tags --}}
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label for="tags">Tags</label>
                <select id="tags" name="tags[]" class="form-control" multiple>
                  @if ($action === 'update')
                    @foreach ($news->tags as $tag)
                      <option value="{{ $tag->id }}" selected>{{ $tag->name }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
          </div>

          {{-- Second Row --}}
          <div class="row">
            <div class="col-12 col-md-6">
              {{-- Is Headline --}}
              <div class="form-group">
                <label for="is_headline">Is Headline</label>
                <select name="is_headline" id="is_headline" class="form-control">
                  <option value="" selected disabled>Is Headline</option>
                  <option value="N" @if (isset($news) && $news->is_headline === 'N') selected @endif>No</option>
                  <option value="Y" @if (isset($news) && $news->is_headline === 'Y') selected @endif>Yes</option>
                </select>
              </div>
            </div>

            {{-- Status --}}
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                  <option value="" selected disabled>Select Status</option>
                  <option value="publish" @if (isset($news) && $news->status === 'publish') selected @endif>Publish</option>
                  <option value="draft" @if (isset($news) && $news->status === 'draft') selected @endif>Draft</option>
                  <option value="pending" @if (isset($news) && $news->status === 'pending') selected @endif>Pending</option>
                </select>
              </div>
            </div>
          </div>

          {{-- Third Row --}}
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label for="summary">Summary</label>
                <textarea name="summary" id="summary" class="form-control h-100" placeholder="Summary"
                  rows="2">{{ $news->summary ?? '' }}</textarea>
              </div>
            </div>

            {{-- Banner Image --}}
            <div class="col-12 col-md-6">
              <div class="form-group">
                {{-- Image Preview --}}
                @if (isset($news))
                  <img src="{{ $news->getBannerImageUrl() }}" alt="{{ $news->getBannerImageUrl() }}"
                    class="img-fluid banner-image-preview my-1">
                @endif

                {{-- Input --}}
                <label for="banner_image">Banner Image</label>
                <input type="file" name="banner_image" id="banner_image" class="form-control-file" accept="image/*"
                  required>
              </div>
            </div>
          </div>

          {{-- Content --}}
          <div class="form-group">
            <label for="content">Content</label>
            <textarea name="content" id="content" class="form-control"
              placeholder="Content">{{ $news->content ?? '' }}</textarea>
          </div>

          <button type="submit" class="d-none"></button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Close</button>
        <button type="button" class="btn {{ $btnType }} btn-submit-alt">{{ ucfirst($action) }}</button>
      </div>
    </div>
  </div>
</div>
