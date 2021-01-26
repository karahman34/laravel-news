<div class="modal fade" id="sync-user-roles-modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sync Roles</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{-- The Form --}}
        <form action="{{ route('administrator.users.sync_roles', ['user' => $user]) }}" method="POST"
          class="need-ajax has-modal">
          @csrf

          <div id="store" class="d-none" data-user-roles="{{ $userRoles }}"></div>

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
              {{-- List of Roles --}}
              @foreach ($roles as $role)
                <tr>
                  <td>{{ $role->name }}</td>
                  <td>
                    <div class="form-check d-flex align-items-center justify-content-center mb-1">
                      <input type="checkbox" class="form-check-input" name="roles[]" value="{{ $role->name }}"
                        @if ($userRoles->firstWhere('name', $role->name)) checked
              @endif />
      </div>
      </td>
      </tr>
      @endforeach
      </tbody>
      </table>

      {{-- Actions --}}
      @include('components.modal-actions', ['action' => 'create'])
      </form>
    </div>
  </div>
</div>
