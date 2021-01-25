<div class="modal fade" id="menu-permissions-modal" role="dialog" aria-hidden="true" data-menu-id="{{ $menu->id }}">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ ucfirst($menu->name) }} Permissions</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{-- Form Block --}}
        <div class="mb-3 form-block">
          <h6 class="action-title">Add Permission</h6>

          {{-- The Form --}}
          <form data-action="create" data-menu-id="{{ $menu->id }}">
            @csrf

            <input type="hidden" name="id">

            {{-- Name --}}
            <div class="form-group mb-2">
              <label for="name">Action Name</label>
              <input type="text" class="form-control" name="name" placeholder="Name" required>
            </div>

            {{-- Actions --}}
            <div class="d-flex justify-content-end my-1">
              <button class="btn btn-danger mr-2">Reset</button>
              <button type="submit" class="btn btn-primary">Create</button>
            </div>
          </form>
        </div>

        {{-- Table --}}
        <table id="menu-permissions-datatable" class="table table-striped has-actions">
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
  </div>
</div>
