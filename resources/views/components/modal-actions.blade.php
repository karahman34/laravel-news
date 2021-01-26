<div class="d-flex justify-content-end">
  {{-- Close --}}
  <button type="button" class="btn btn-light mr-3" data-dismiss="modal">Close</button>
  {{-- Submit --}}
  <button type="submit"
    class="btn btn-{{ $action === 'create' ? 'primary' : 'warning' }}">{{ ucwords($action) }}</button>
</div>
