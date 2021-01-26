@php
$action = isset($user) ? 'update' : 'create';
$modalTitle = $action === 'create' ? 'Create User' : 'Edit' . $user->name;
$method = $action === 'create' ? 'POST' : 'PATCH';
$url = $action === 'create' ? route('administrator.users.store') :
route('administrator.users.update', ['user' =>
$user]);
@endphp

<div class="modal fade" id="user-form-modal" role="dialog" aria-hidden="true">
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
          data-datatable="#users-datatable" @if ($action === 'update') data-stay-paging="1" @endif>
          @csrf @method($method)

          {{-- Email --}}
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" class="form-control" name="email" placeholder="Email"
              value="{{ $user->email ?? '' }}" required autofocus>
          </div>

          {{-- Name --}}
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" class="form-control" name="name" placeholder="Name"
              value="{{ $user->name ?? '' }}" required>
          </div>

          {{-- Password --}}
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" class="form-control" name="password" placeholder="Password" @if ($action === 'create') required @endif>
          </div>

          {{-- Repeat Password --}}
          <div class="form-group">
            <label for="password_confirmation">Repeat Password</label>
            <input type="password" class="form-control" name="password_confirmation" placeholder="Repeat Password">
          </div>

          {{-- Actions --}}
          @include('components.modal-actions', compact('action'))
        </form>
      </div>
    </div>
  </div>
</div>
