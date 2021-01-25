@extends('layouts.default.layout')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4>
        {{ ucwords($activeMenu->name) }}
      </h4>
    </div>

    <div class="card-body">
      <table class="table has-actions" id="menu-datatable">
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection

@push('script')
  <script>
    $('#menu-datatable').DataTable({
      serverSide: true,
      responsive: true,
      ajax: "{{ route('administrator.user-managements.permissions.index') }}",
      columns: [{
        data: 'id'
      }, {
        data: 'name'
      }]
    })

  </script>
@endpush
