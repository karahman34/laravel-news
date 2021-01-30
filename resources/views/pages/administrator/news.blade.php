@extends('layouts.default.layout')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4>
        <i class="{{ $activeMenu->icon }}"></i>
        {{ ucwords($activeMenu->name) }}
      </h4>

      <div class="d-flex justify-content-end align-items-center">
        {{-- Export --}}
        @can('export', App\Models\News::class)
          @include('components.button.export-btn', ['action' => route('administrator.news.export')])
        @endcan

        {{-- Import --}}
        @can('import', App\Models\News::class)
          @include('components.button.import-btn', ['action' => route('administrator.news.import')])
        @endcan

        {{-- Create --}}
        @can('create', App\Models\News::class)
          @include('components.button.create-btn', ['action' => route('administrator.news.create')])
        @endcan
      </div>
    </div>

    <div class="card-body">
      <table id="news-datatable" class="table has-actions">
        <thead>
          <tr>
            <th>Id</th>
            <th>Author</th>
            <th>Title</th>
            <th>Views</th>
            <th>Is Headline</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection

@push('css')
  <style>
    .banner-image-preview {
      display: block;
      max-height: 300px;
    }

  </style>
@endpush

@push('script')
  <script src="//cdn.ckeditor.com/4.15.1/standard/ckeditor.js"></script>
  <script>
    // Initialize DataTable
    $('#news-datatable').DataTable({
      serverSide: true,
      responsive: true,
      ajax: "{{ route('administrator.news.index') }}",
      columns: [{
        data: 'id'
      }, {
        data: 'author.name',
      }, {
        data: 'title'
      }, {
        data: 'views'
      }, {
        data: 'is_headline'
      }, {
        data: 'status'
      }, {
        data: 'created_at',
        render: function(data) {
          return moment(data).calendar()
        }
      }, {
        data: 'updated_at',
        render: function(data) {
          return moment(data).calendar()
        }
      }, {
        data: 'actions',
        orderable: false,
        searchable: false,
        render: function(data) {
          if (!data.length) {
            return `<span class="text-muted font-italic">No Actions</span>`
          }

          return data
        }
      }, ]
    })

    // Set working variables.
    let $btnSpinner = null
    const modalSelector = '#news-form-modal'

    CKEDITOR.plugins.add('dropoff', {
      init: function(editor) {

        function rejectDrop(event) {
          event.data.preventDefault(true);
        };

        editor.on('contentDom', function() {
          editor.document.on('drop', rejectDrop);
        });
      }
    });

    // Form Modal Opened
    $(document).on('api-modal.loaded', function(e, modal) {
      if (modal !== modalSelector) return

      // Initialize select2.
      $(`${modalSelector} form select:not([id=tags])`).select2({
        minimumResultsForSearch: Infinity,
      })

      // Initialize select2 -- tags.
      $(`${modalSelector} form select[id=tags]`).select2({
        placeholder: 'Select Tags',
        minimumInputLength: 1,
        dropdownParent: $(modalSelector),
        ajax: {
          url: "{{ route('administrator.tags.search') }}",
          delay: 250,
          data: function(params) {
            const query = {
              q: params.term,
              page: params.page || 1,
            }

            return query
          },
          processResults: function({
            data
          }) {
            return {
              results: data.data.map(tag => ({
                id: tag.id,
                text: tag.name,
              })),
              pagination: {
                more: data.current_page < data.last_page
              }
            }
          }
        }
      });

      // Initialize CKEditor.
      CKEDITOR.replace('content', {
        filebrowserUploadUrl: "{{ route('administrator.news.upload', ['_token' => csrf_token()]) }}",
        filebrowserUploadMethod: 'form',
        extraPlugins: 'dropoff'
      })
    })

    // On banner image upload
    $(document).on('change', `${modalSelector} input[type=file][name=banner_image]`, function(e) {
      e.preventDefault()

      const $input = $(this)
      const file = e.target.files[0]
      const $parent = $input.parent()

      // Remove the img preview.
      $parent.find('img').remove()

      if (file) {
        const previewImage = URL.createObjectURL(file)
        $parent.prepend(`<img class="banner-image-preview my-1" src="${previewImage}" alt="${previewImage}" />`)
      } else {
        // Clear the input
        $input.val('')
      }
    })

    // Click on submit button
    $(document).on('click', `${modalSelector} .modal-footer .btn-submit-alt`, function(e) {
      e.preventDefault()

      const $modal = $(modalSelector)
      const contentInput = $modal.find('textarea#content').val()

      // Update ckeditor instances.
      for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
      }

      // Trigger form submit
      $modal.find('form').trigger('submit')

      // Show loading
      $btnSpinner = new ButtonSpinner($(this))
      $btnSpinner.show()
    })

    // Form was submitted
    $(document).on('form-ajax.submitted', function(e, res, $form) {
      if ($form.attr('id') !== 'news-form') return

      $btnSpinner.hide()
      $btnSpinner = null
    })

  </script>
@endpush
