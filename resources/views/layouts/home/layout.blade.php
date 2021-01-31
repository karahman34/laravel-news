<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,300;0,400;0,600;0,700;1,400;1,600&display=swap"
    rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/home.css') }}">

  <title>@isset($title) {{ $title }} -@endisset {{ env('APP_NAME') }}</title>

  @livewireStyles

  @stack('css')
</head>

<body>
  <div id="app">
    {{-- Navbar --}}
    @include('layouts.home.navbar')

    {{-- Popular Tags --}}
    @include('layouts.home.navbar-tags')

    {{-- Body Layout --}}
    <div class="row container px-0 mt-4 mx-auto">
      {{-- Content --}}
      <div class="col-12 col-md-8">
        <section>
          @yield('content')
        </section>
      </div>

      {{-- Sidebar --}}
      <div class="col-12 col-md-4">
        <aside id="sidebar">
          @include('layouts.home.popular-tags-card')
          <br>
          @include('layouts.home.popular-news')
        </aside>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
  </script>

  @livewireScripts

  <script>
    document.addEventListener('scroll', () => {
      const sidebar = document.querySelector('aside#sidebar')
      const navbar = document.querySelector('#my-navbar')
      const navbarTags = document.querySelector('#navbar-tags')

      const windowWidth = window.innerWidth || document.documentElement.clientWidth
      const scrollY = window.scrollY + window.innerHeight
      const stopAt = sidebar.scrollHeight + navbar.scrollHeight + navbarTags.scrollHeight
      const sidebarTopPosition = window.innerHeight - sidebar.scrollHeight

      if (windowWidth < 768) {
        sidebar.style.display = 'none'
        return
      } else {
        sidebar.style.display = 'block'
      }

      if (scrollY >= stopAt) {
        sidebar.style.maxWidth = sidebar.scrollWidth + 'px'
        sidebar.style.position = 'fixed'
        sidebar.style.top = -Math.abs(sidebarTopPosition) + 'px'
      } else {
        sidebar.style.position = 'static'
        sidebar.style.maxWidth = '100%'
      }
    })

  </script>

  @stack('script')
</body>

</html>
