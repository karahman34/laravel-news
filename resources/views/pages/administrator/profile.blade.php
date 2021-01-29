@extends('layouts.default.layout')

@section('content')
  <div class="card">
    <div class="card-header">
      <h4>
        <i class="fas fa-user fa-2x mr-1"></i>
        Profile
      </h4>
    </div>

    <div class="card-body">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-5">
          {{-- The Form --}}
          <form id="update-profile-form" method="POST" action="{{ route('administrator.profile.update') }}"
            class="need-ajax">
            @csrf @method('PATCH')

            {{-- Name --}}
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" id="name" class="form-control" value="{{ $user->name }}" disabled>
            </div>

            {{-- Email --}}
            <div class="form-group">
              <label for="email">Email</label>
              <input type="text" id="email" class="form-control" value="{{ $user->email }}" disabled>
            </div>

            {{-- Password --}}
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" class="form-control" placeholder="Password">
            </div>

            {{-- Password --}}
            <div class="form-group">
              <label for="password_confirmation">Repeat Password</label>
              <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                placeholder="Password">
            </div>

            {{-- Actions --}}
            <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
