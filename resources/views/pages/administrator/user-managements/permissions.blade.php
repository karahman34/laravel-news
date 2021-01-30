@extends('layouts.default.layout')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4>
        {{ ucwords($activeMenu->name) }}
      </h4>

      <div class="d-flex align-items-center">
        {{-- Export --}}
        @can('export', Spatie\Permission\Models\Permission::class)
          @include('components.button.export-btn', ['action' => route('administrator.user-managements.permissions.export')])
        @endcan

        {{-- Import --}}
        @can('import', Spatie\Permission\Models\Permission::class)
          @include('components.button.import-btn', ['action' => route('administrator.user-managements.permissions.import')])
        @endcan
      </div>
    </div>

    <div class="card-body">
      <table class="table has-actions" id="permissions-datatable">
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
@endsection

@push('script')
  <script>
    $('#permissions-datatable').DataTable({
      serverSide: true,
      responsive: true,
      ajax: "{{ route('administrator.user-managements.permissions.index') }}",
      columns: [{
        data: 'id'
      }, {
        data: 'name'
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
        orderable: false,
        searchable: false,
        render: function(data) {
          if (!data.length) {
            return `<span class="text-muted font-italic">No Actions</span>`
          }

          return data
        }
      }]
    })

  </script>
@endpush
