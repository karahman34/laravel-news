@extends('layouts.default.layout')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4>
        {{ ucwords($activeMenu->name) }}
      </h4>

      <div class="d-flex justify-content-end align-items-center">
        {{-- Create Button --}}
        @can('users-create')
          <a href="{{ route('administrator.users.create') }}" class="btn btn-primary btn-modal-trigger"
            data-modal="#user-form-modal">
            <i class="fas fa-plus mr-1"></i>
            Create
          </a>
        @endcan
      </div>
    </div>

    <div class="card-body">
      <table id="users-datatable" class="table table-striped has-actions">
        <thead>
          <tr>
            <th>Id</th>
            <th>Email</th>
            <th>Name</th>
            <th>Roles</th>
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
    // Initialize datatable.
    $('#users-datatable').DataTable({
      serverSide: true,
      responsive: true,
      ajax: "{{ route('administrator.users.index') }}",
      columns: [{
        data: 'id'
      }, {
        data: 'name'
      }, {
        data: 'email',
      }, {
        data: 'roles',
        render: function(data) {
          return data.length ?
            data :
            '<span class="text-muted font-italic">null</span>'
        }
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
        searchable: false,
        orderable: false,
        render: function(data) {
          if (!data.length) {
            return `<span class="text-muted font-italic">No Actions</span>`
          }

          return data
        }
      }]
    })

    const modalSelector = '#sync-user-roles-modal'
    const formId = 'sync-user-roles-form'
    let $btnSpinner = null
    let roles = []
    let selectedRoles = []

    function appendCheckBox($tBody, role, selectedRoles) {
      $tBody.append(`
                  <tr>
                    <td>${role}</td>
                    <td>
                      <div class="form-check d-flex align-items-center justify-content-center mb-1">
                        <input type="checkbox" class="form-check-input" name="roles[]" value="${role}" ${selectedRoles.some(r => r === role) ? 'checked' : ''} />  
                      </div>  
                    </td>
                  </tr>
                `)
    }

    // When modal open
    $(document).on('api-modal.loaded', function(e, modal) {
      if (modal !== modalSelector) return

      const $modal = $(this)
      const $rolesChecked = $modal.find('input[type=checkbox]:checked')
      const $rolesCheckbox = $modal.find('input[type=checkbox]')

      // Set roles list
      $rolesCheckbox.each(function() {
        roles.push($(this).val())
      })

      // Set default selected roles
      $rolesChecked.each(function() {
        const $el = $(this)
        selectedRoles.push($el.val())
      })
    })

    // Reset variables on modal close
    $(document).on('api-modal.removed', function(e, modal) {
      if (modal === modalSelector) {
        $btnSpinner = null
        roles = []
        selectedRoles = []
      }
    })

    // Listen checkbox change
    $(document).on('change', `${modalSelector} input[type=checkbox]`, function(e) {
      const $el = $(this)
      const val = $el.val()

      selectedRoles.indexOf(val) === -1 ?
        selectedRoles.push(val) :
        selectedRoles.splice(selectedRoles.indexOf(val), 1)
    })

    // Click on sync button / submit
    $(document).on('click', `${modalSelector} .btn-submit-alt`, function(e) {
      e.preventDefault()

      // Show loading spinner
      $btnSpinner = new ButtonSpinner($(this))
      $btnSpinner.show()

      // Reset tbody
      const $tBody = $(`${modalSelector} table tbody`)
      $tBody.html('')

      // Append the full roles
      roles.forEach((role) => appendCheckBox($tBody, role, selectedRoles))

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
      const filteredRoles = !q.length ? roles : roles.filter(role => role.includes(q))

      $tBody.html('')

      if (!filteredRoles.length) {
        $tBody.append(`
                      <tr>
                        <td colspan="2" class="text-center">Roles not found.</td>  
                      </tr>
                    `)
      } else {
        filteredRoles.forEach((role) => appendCheckBox($tBody, role, selectedRoles))
      }
    })

  </script>
@endpush
