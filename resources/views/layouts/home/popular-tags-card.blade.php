<div class="card bg-primary">
  <div class="card-body px-2">
    <h5 class="px-1 card-title text-white">
      <i class="fas fa-tags fa-sm mr-1"></i>
      Popular Tags
    </h5>

    @foreach ($popularTags as $tag)
      <a href="{{ route('search') }}?tags={{ $tag->name }}"
        class="btn btn-light py-1 px-2 mx-1 my-1">{{ $tag->name }}</a>
    @endforeach
  </div>
</div>
