@extends('layouts.default.layout')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4>
        {{ ucwords($activeMenu->name) }}
      </h4>

      <div class="d-flex justify-content-end">
        {{-- Create --}}
        <a href="{{ route('administrator.user-managements.roles.create') }}" class="btn btn-primary btn-modal-trigger"
          data-modal="#form-role-modal">
          <i class="fas fa-plus mr-1"></i>
          Create
        </a>
      </div>
    </div>

    <div class="card-body">
      <table class="table has-actions" id="roles-datatable">
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection

@push('script')
  <script>
    $('#roles-datatable').DataTable({
      serverSide: true,
      responsive: true,
      ajax: "{{ route('administrator.user-managements.roles.index') }}",
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
        searchable: false
      }]
    })

    const modalSelector = '#role-permissions-modal'
    const formId = 'sync-role-permissions-form'
    let $btnSpinner = null
    let rolePermissions = []
    let permissions = []
    let selectedPermissions = []

    // Modal loaded
    $(document).on('api-modal.loaded', function(e, modal) {
      if (modal !== modalSelector) return

      const $modal = $(this)
      const $store = $modal.find('#store')
      const $permissionChecked = $modal.find('input[type=checkbox]:checked')
      const $permissionCheckbox = $modal.find('input[type=checkbox]')

      // Set role permissions
      rolePermissions = Array.from($store.data('role-permissions')).map(rp => rp.name)

      // Set permissions list
      $permissionCheckbox.each(function() {
        permissions.push($(this).val())
      })

      // Set default checked
      $permissionChecked.each(function() {
        const $el = $(this)
        selectedPermissions.push($el.val())
      })
    })

    // Modal removed
    $(document).on('api-modal.removed', function(e, modal) {
      if (modal === modalSelector) {
        $btnSpinner = null
        rolePermissions = []
        permissions = []
        selectedPermissions = []
      }
    })

    // Checkbox change
    $(document).on('change', `${modalSelector} input[type=checkbox]`, function() {
      const $el = $(this)
      const val = $el.val()

      selectedPermissions.indexOf(val) === -1 ?
        selectedPermissions.push(val) :
        selectedPermissions.splice(selectedPermissions.indexOf(val), 1)
    })

    // Click on sync button / submit
    $(document).on('click', `${modalSelector} .btn-submit-alt`, function(e) {
      e.preventDefault()

      // Show spinner
      $btnSpinner = new ButtonSpinner($(this))
      $btnSpinner.show()

      // Trigger form submit
      const $form = $(`${modalSelector} #${formId}`)
      $form.trigger('submit')
    })

    // Form was submitted.
    $(document).on('form-ajax.submitted', function(e, res, $form) {
      if ($form.attr('id') === formId) {
        $btnSpinner.hide()
      }
    })

    // Listen search input
    $(document).on('input', `${modalSelector} #search-input`, function(e) {
      e.preventDefault()

      const $modal = $(modalSelector)
      const $tBody = $modal.find('table tbody')
      const q = $(this).val()
      const filteredPermissions = !q.length ? permissions : permissions.filter(permission => permission.includes(q))

      // Reset tbody
      $tBody.html('')

      if (!filteredPermissions.length) {
        $tBody.append(`
                  <tr>
                    <td colspan="2">Permissions not found.</td>
                  </tr>
                `)
      } else {
        filteredPermissions.forEach((permission) => {
          $tBody.append(`
                          <tr>
                            <td>${permission}</td>
                            <td>
                              <div class="form-check d-flex align-items-center justify-content-center mb-1">
                                <input type="checkbox" class="form-check-input" name="permissions[]" value="${permission}" ${selectedPermissions.some(p => p === permission) ? 'checked' : ''} />  
                              </div>  
                            </td>
                          </tr>
                        `)
        })
      }
    })

  </script>
@endpush
