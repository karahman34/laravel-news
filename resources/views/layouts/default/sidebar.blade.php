<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="index.html">Stisla</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="index.html">St</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>

      {{-- List of Menus --}}
      @foreach ($menus as $menu)
        @if ($menu->has_sub_menus === 'N')
          {{-- Single Menus --}}
          <li @if (request()->is(substr($menu->path, 1))) class="active" @endif>
            <a class="nav-link" href="{{ $menu->path }}">
              <i class="{{ $menu->icon }}"></i>
              <span>{{ ucwords($menu->name) }}</span>
            </a>
          </li>
        @else
          {{-- Dropdown Menus --}}
          <li class="nav-item dropdown @if (request()->is(substr($menu->path, 1))) active @endif">
            <a href="#" class="nav-link has-dropdown">
              <i class="{{ $menu->icon }}"></i>
              <span>{{ $menu->name }}</span>
            </a>

            {{-- Sub Menus --}}
            <ul class="dropdown-menu">
              @if (!is_null($menu->sub_menus) && $menu->sub_menus->count() > 0)
                @foreach ($menu->sub_menus as $subMenu)
                  <li @if (request()->is(substr($subMenu->path, 1))) class="active" @endif>
                    <a class="nav-link" href="{{ $subMenu->path }}">{{ ucwords($subMenu->name) }}</a>
                  </li>
                @endforeach
              @endif
            </ul>
          </li>
        @endif
      @endforeach
    </ul>
  </aside>
</div>
