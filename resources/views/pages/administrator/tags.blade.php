@extends('layouts.default.layout')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4>
        <i class="{{ $activeMenu->icon }}"></i>
        {{ ucwords($activeMenu->name) }}
      </h4>

      <div class="d-flex justify-content-end align-items-center">
        {{-- Create --}}
        <a href="{{ route('administrator.tags.create') }}" class="btn btn-primary btn-modal-trigger"
          data-modal="#form-tag-modal">
          <i class="fas fa-plus mr-2"></i>
          Create
        </a>
      </div>
    </div>

    <div class="card-body">
      <table id="tags-datatable" class="table has-actions">
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Views</th>
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
    $('#tags-datatable').DataTable({
      serverSide: true,
      responsive: true,
      ajax: "{{ route('administrator.tags.index') }}",
      columns: [{
        data: 'id'
      }, {
        data: 'name'
      }, {
        data: 'views'
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
      }, ]
    })

  </script>
@endpush
