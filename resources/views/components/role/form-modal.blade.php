@php
$action = isset($role) ? 'update' : 'create';
$modalTitle = $action === 'create' ? 'Create' : 'Edit' . $role->name;
$method = $action === 'create' ? 'POST' : 'PATCH';
$url = $action === 'create' ? route('administrator.user-managements.roles.store') :
route('administrator.user-managements.roles.update', ['role' =>
$role]);

$btnType = $action === 'create' ? 'btn-primary' : 'btn-warning';
@endphp

<div class="modal fade" id="form-role-modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ $modalTitle }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{-- The Form --}}
        <form action="{{ $url }}" method="POST" class="need-ajax has-datatable has-modal"
          data-datatable="#roles-datatable" @if ($action === 'update') data-stay-paging="1" @endif>
          @csrf @method($method)

          {{-- Name --}}
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Name"
              value="{{ $role->name ?? '' }}" required autofocus>
          </div>

          {{-- Actions --}}
          @include('components.modal-actions', ['action' => $action])
        </form>
      </div>
    </div>
  </div>
</div>
