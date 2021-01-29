<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ route('administrator.dashboard') }}">{{ env('APP_NAME') }}</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{ route('administrator.dashboard') }}">{{ env('APP_NAME') }}</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">General</li>

      {{-- List of Menus --}}
      @foreach ($menus as $menu)
        @if ($menu->has_sub_menus === 'N')
          {{-- Single Menus --}}
          <li @if (request()->getPathInfo() === $menu->path) class="active" @endif>
            <a class="nav-link" href="{{ $menu->path }}">
              <i class="{{ $menu->icon }}"></i>
              <span>{{ ucwords(strtolower($menu->name)) }}</span>
            </a>
          </li>
        @else
          {{-- Dropdown Menus --}}
          <li class="nav-item dropdown @if ($menu->sub_menus->firstWhere('path',
            request()->getPathInfo())) active @endif">
            <a href="#" class="nav-link has-dropdown">
              <i class="{{ $menu->icon }}"></i>
              <span>{{ ucwords(strtolower($menu->name)) }}</span>
            </a>

            {{-- Sub Menus --}}
            <ul class="dropdown-menu">
              @if (!is_null($menu->sub_menus) && $menu->sub_menus->count() > 0)
                @foreach ($menu->sub_menus as $subMenu)
                  <li @if (request()->getPathInfo() === $subMenu->path) class="active" @endif>
                    <a class="nav-link" href="{{ $subMenu->path }}">{{ ucwords(strtolower($subMenu->name)) }}</a>
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
