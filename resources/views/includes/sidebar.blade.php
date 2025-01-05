<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item {{ Route::currentRouteNamed('admin') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item {{ Route::is('user.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.index') }}">
                <i class="ti-user menu-icon"></i>
                <span class="menu-title">User Management</span>
            </a>
        </li>
        <li class="nav-item {{ Route::is('province.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('province.index') }}">
                <i class="ti-map-alt menu-icon"></i>
                <span class="menu-title">Province Master</span>
            </a>
        </li>
    
        <li class="nav-item {{ Route::is('budget.*') ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('budget.index') }}">
                <i class="ti-briefcase menu-icon"></i>
                <span class="menu-title">Budgets</span>
            </a>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link">
                <i class="ti-briefcase menu-icon"></i>
                <span class="menu-title">Budget Relocations</span>
            </a>
        </li> --}}

        <li class="nav-item">
            <a class="nav-link">
                <i class="ti-clipboard menu-icon"></i>
                <span class="menu-title">User Entertain</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link">
                <i class="ti-settings menu-icon"></i>
                <span class="menu-title">App Setting</span>
            </a>
        </li>
        

        {{-- <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false"
                aria-controls="ui-basic">
                <i class="icon-layout menu-icon"></i>
                <span class="menu-title">UI Elements</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link"
                            href="pages/ui-features/buttons.html">Buttons</a></li>
                    <li class="nav-item"> <a class="nav-link"
                            href="pages/ui-features/dropdowns.html">Dropdowns</a></li>
                    <li class="nav-item"> <a class="nav-link"
                            href="pages/ui-features/typography.html">Typography</a></li>
                </ul>
            </div>
        </li> --}}
        
    </ul>
</nav>