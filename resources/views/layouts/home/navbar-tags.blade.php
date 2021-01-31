@php
$tags = ['metro', 'sport', 'travel', 'ticket', 'lifestyle', 'destination', 'technology', 'food', 'drama', 'shopping', 'business', 'news', 'community'];
@endphp

<nav id="navbar-tags" class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <div class="collapse navbar-collapse" id="navbarPopularTagsContnt">
      <ul class="navbar-nav">
        @foreach ($tags as $tag)
          <li class="nav-item">
            <a class="nav-link text-white"
              href="{{ route('search') }}?tags={{ $tag }}">{{ strtoupper($tag) }}</a>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
</nav>
