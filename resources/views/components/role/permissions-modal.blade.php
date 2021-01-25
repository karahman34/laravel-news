<div class="modal fade" id="role-permissions-modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sync Permissions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{-- The Form --}}
        <form action="{{ route('administrator.user-managements.roles.sync_permissions', ['role' => $role]) }}"
          method="POST" class="need-ajax has-modal">
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

      {{-- Actions --}}
      <div class="d-flex justify-content-end">
        {{-- Close --}}
        <button class="btn btn-light mr-3" data-dismiss="modal">Close</button>
        {{-- Submit --}}
        <button type="submit" class="btn btn-primary">Sync</button>
      </div>
      </form>
    </div>
  </div>
</div>
