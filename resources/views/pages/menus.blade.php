@extends('layouts.default.layout')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4>
        <i class="{{ $activeMenu->icon }} mr-1"></i>
        {{ ucwords($activeMenu->name) }}
      </h4>

      <div class="d-flex justify-content-end">
        {{-- Create --}}
        <a href="{{ route('administrator.menus.create') }}" class="btn btn-primary btn-modal-trigger"
          data-modal="#form-menu-modal">
          <i class="fas fa-plus mr-1"></i>
          Create
        </a>
      </div>
    </div>

    <div class="card-body">
      <table class="table has-actions" id="menu-datatable">
        <thead>
          <tr>
            <th>Id</th>
            <th>Parent Id</th>
            <th>Icon</th>
            <th>Name</th>
            <th>Path</th>
            <th>Has Sub Menus</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection

@push('script')
  <script>
    $('#menu-datatable').DataTable({
      serverSide: true,
      responsive: true,
      ajax: "{{ route('administrator.menus.index') }}",
      columns: [{
        data: 'id'
      }, {
        data: 'parent_id',
        render: function(data) {
          return data || '<span class="text-muted">null</span>'
        }
      }, {
        data: 'icon',
        render: function(data) {
          return data || '<span class="text-muted">null</span>'
        }
      }, {
        data: 'name'
      }, {
        data: 'path'
      }, {
        data: 'has_sub_menus'
      }, {
        data: 'actions',
        orderable: false,
        searchable: false
      }]
    })

    $(document).on('api-modal.loaded', function(e, modal) {
      const modalSelector = '#form-menu-modal'

      if (modal !== modalSelector) return

      const $iconInput = $('.form-group .form-control[name=icon]')
      const $parentIdInput = $('.form-group .form-control[name=parent_id]')

      function disableIcon() {
        $iconInput.attr('disabled', 'disabled')
        $iconInput.attr('required', null)
      }

      function enableIcon() {
        $iconInput.attr('disabled', null)
        $iconInput.attr('required', 'required')
      }

      $parentIdInput.val().length ? disableIcon() : enableIcon()

      $parentIdInput.keyup(function(e) {
        $parentIdInput.val().length ? disableIcon() : enableIcon()
      })
    })

  </script>
@endpush
