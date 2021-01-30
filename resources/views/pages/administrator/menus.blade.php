@extends('layouts.default.layout')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4>
        <i class="{{ $activeMenu->icon }} mr-1"></i>
        {{ ucwords($activeMenu->name) }}
      </h4>

      <div class="d-flex justify-content-end">
        {{-- Export --}}
        @can('export', App\Models\Menu::class)
          @include('components.button.export-btn', ['action' => route('administrator.menus.export')])
        @endcan

        {{-- Import --}}
        @can('import', App\Models\Menu::class)
          @include('components.button.import-btn', ['action' => route('administrator.menus.import')])
        @endcan

        {{-- Create --}}
        @can('create', App\Models\Menu::class)
          @include('components.button.create-btn', [
          'action' => route('administrator.menus.create'),
          'modal' => '#form-menu-modal'
          ])
        @endcan
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
        searchable: false,
        render: function(data) {
          if (!data.length) {
            return `<span class="text-muted font-italic">No Actions</span>`
          }

          return data
        }
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
        $iconInput.val('')
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

    const modalSelector = '#menu-permissions-modal'
    $(document).on('api-modal.loaded', function(e, modal) {

      if (modal !== modalSelector) return

      const $modal = $(modalSelector)
      const $dataTable = $modal.find('table')
      const menuId = $modal.data('menu-id')

      $dataTable.DataTable({
        serverSide: true,
        responsive: true,
        ajax: `/administrator/menus/${menuId}/permissions`,
        columns: [{
          data: 'id'
        }, {
          data: 'name'
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
        }]
      })
    })

    // Edit
    $(document).on('click', `${modalSelector} .btn.btn-warning`, function(e) {
      e.preventDefault()

      const $btn = $(this)
      const permissionId = $btn.data('permission-id')
      const permissionName = $btn.data('permission-name').split('-')
      const $modal = $(modalSelector)
      const $form = $modal.find('form')
      $form.data('action', 'update')
      $form.find('input[name=id]').val(permissionId)
      $form.find('input[name=name]').val(permissionName[permissionName.length - 1])
      $form.find('button[type=submit]').html('Update')

      $modal.find('.action-title').html('Edit Permission')
    })

    // Reset Form
    $(document).on('click', `${modalSelector} .btn.btn-danger`, function(e) {
      e.preventDefault()

      const $btn = $(this)
      const $modal = $(modalSelector)
      const $form = $modal.find('form')
      $form.data('action', 'create')
      $form.find('input[name=id]').val(null)
      $form.find('input[name=name]').val(null)
      $form.find('button[type=submit]').html('Create')

      $modal.find('.action-title').html('Add Permission')
    })

    // Submit
    $(document).on('submit', `${modalSelector} form`, function(e) {
      e.preventDefault()

      const $form = $(this)
      const menuId = $form.data('menu-id')
      const actionType = $form.data('action')

      const inputCSRF = $form.find('input[name=_token]')
      const inputId = $form.find('input[name=id]').val()
      const inputName = $form.find('input[name=name]').val()

      const url = actionType === 'create' ?
        `/administrator/menus/${menuId}/permissions` :
        `/administrator/menus/${menuId}/permissions/${inputId}`

      const $btnSpinner = new ButtonSpinner($form.find('button[type=submit]'))
      $btnSpinner.show()

      const payload = {
        _token: CSRF_TOKEN,
        name: inputName
      }

      if (actionType === 'update') {
        payload['_method'] = 'PATCH'
      }

      $.post(url, payload)
        .done(res => {
          const $modal = $(modalSelector)
          $modal.find('table').DataTable().ajax.reload(null, false)

          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: res.message || 'Success to add permission!',
          })

          $form.find('.btn.btn-danger').trigger('click')
        })
        .fail(err => Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: err.responseJSON.message || 'Error while submitting data.',
        }))
        .always(() => $btnSpinner.hide())
    })

  </script>
@endpush
