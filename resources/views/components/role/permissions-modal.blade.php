<div class="modal fade" id="role-permissions-modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sync {{ ucwords($role->name) }} Permissions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pb-0">
        {{-- The Form --}}
        <form id="sync-role-permissions-form"
          action="{{ route('administrator.user-managements.roles.sync_permissions', ['role' => $role]) }}" method="POST"
          class="need-ajax has-modal">
          @csrf

          <div id="store" class="d-none" data-role-permissions="{{ $rolePermissions }}"></div>

          {{-- Search --}}
          <div class="form-group mb-1">
            <input id="search-input" type="text" class="form-control" placeholder="Search">
          </div>

          <table class="table table-striped text-center">
            <thead>
              <tr>
                <th>Name</th>
                <th>Value</th>
              </tr>
            </thead>

            <tbody>
              {{-- List of Permissions --}}
              @foreach ($permissions as $permission)
                <tr>
                  <td>{{ $permission->name }}</td>
                  <td>
                    <div class="form-check d-flex align-items-center justify-content-center mb-1">
                      <input type="checkbox" class="form-check-input" name="permissions[]"
                        value="{{ $permission->name }}" @if ($rolePermissions->firstWhere('name', $permission->name)) checked
              @endif />
      </div>
      </td>
      </tr>
      @endforeach
      </tbody>
      </table>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary btn-submit-alt">Sync</button>
    </div>
  </div>
</div>
