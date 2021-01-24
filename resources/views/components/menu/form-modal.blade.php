@php
$action = isset($menu) ? 'update' : 'create';
$modalTitle = $action === 'create' ? 'Create' : 'Edit' . $menu->name;
$method = $action === 'create' ? 'POST' : 'PATCH';
$url = $action === 'create' ? route('administrator.menus.store') : route('administrator.menus.update', ['menu' =>
$menu]);

$btnType = $action === 'create' ? 'btn-primary' : 'btn-warning';
@endphp

<div class="modal fade" id="form-menu-modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ $modalTitle }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{-- Note --}}
        <div class="alert alert-info mb-2">
          <h6>Note:</h6>

          <ul class="mb-0">
            <li>The <b>Parent Id</b> field is optional.</li>
            <li>You don't need to input the <b>parent path</b> menu, the app wil recognize it automatically.</li>
          </ul>
        </div>

        {{-- The Form --}}
        <form action="{{ $url }}" method="POST" class="need-ajax has-datatable has-modal"
          data-datatable="#menu-datatable" @if ($action === 'update') data-stay-paging="1" @endif>
          @csrf @method($method)

          {{-- Parent Id --}}
          <div class="form-group">
            <label for="parent_id">Parent Id</label>
            <input type="number" name="parent_id" id="parent_id" class="form-control" placeholder="Parent Id"
              value="{{ $menu->parent_id ?? '' }}">
          </div>

          {{-- Name --}}
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Name"
              value="{{ $menu->name ?? '' }}" required autofocus>
          </div>

          {{-- Icon --}}
          <div class="form-group">
            <label for="icon">Icon</label>
            <input type="text" name="icon" id="icon" class="form-control" placeholder="Icon"
              value="{{ $menu->icon ?? '' }}">
          </div>

          {{-- Current Path --}}
          @if ($action === 'update' && $menu->path)
            <div class="form-group">
              <label for="current_path">Current Path</label>
              <input type="text" id="current_path" class="form-control" placeholder="Current Path"
                value="{{ $menu->path ?? '' }}" disabled>
            </div>
          @endif

          {{-- New Path --}}
          <div class="form-group">
            <label for="path">
              @if ($action === 'create') Path @else New Path @endif
            </label>
            <input type="text" name="path" id="path" class="form-control" placeholder="Path" @if ($action === 'create') required @endif>
          </div>

          {{-- Sub Menus --}}
          <div class="form-group">
            <label for="has_sub_menus">Has Sub Menus</label>
            <select name="has_sub_menus" id="has_sub_menus" class="form-control">
              <option value="N" selected>No</option>
              <option value="Y" @if (isset($menu) && $menu->has_sub_menus === 'Y') selected @endif>Yes</option>
            </select>
          </div>

          <div class="d-flex justify-content-end">
            {{-- Close --}}
            <button class="btn btn-light mr-3" data-dismiss="modal">Close</button>
            {{-- Submit --}}
            <button type="submit" class="btn {{ $btnType }}">{{ ucfirst($action) }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
