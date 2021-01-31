<nav id="my-navbar" class="navbar navbar-expand-lg bg-primary text-white navbar-dark">
  <div class="container">
    {{-- Brand / Logo --}}
    <a class="navbar-brand text-white" href="{{ route('welcome') }}">{{ env('APP_NAME') }}</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#myNavbarSuppoerContent"
      aria-controls="myNavbarSuppoerContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="myNavbarSuppoerContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item search mr-2">
          <form action="{{ route('search') }}">
            <i class="fas fa-search text-muted"></i>
            <input type="search" name="q" placeholder="Search" autocomplete="off">
          </form>
        </li>
        <li class="nav-item social">
          <a class="nav-link text-white" href="https://www.facebook.com" target="_blank"><i
              class="fab fa-facebook fa-lg"></i></a>
          <a class="nav-link text-white" href="https://www.twitter.com" target="_blank"><i
              class="fab fa-twitter fa-lg"></i></a>
          <a class="nav-link text-white" href="https://www.instagram.com" target="_blank"><i
              class="fab fa-instagram fa-lg"></i></a>
        </li>
      </ul>
    </div>
  </div>
</nav>
