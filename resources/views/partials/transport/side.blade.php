<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item {{ menuActive('transport.dashboard') }}">
            <a class="nav-link" href="{{ route('transport.dashboard') }}">
                <i class="las la-tachometer-alt menu-icon"></i>
                <span class="menu-title">@lang('constants.dashboard') </span>
            </a>
        </li>
        <li class="nav-item {{ menuActive('transport.driver.*') }}">
            <a class="nav-link" data-toggle="collapse" href="#driver-menu"
                aria-expanded="{{ menuActive('transport.driver.*', 2) }}" aria-controls="driver-menu">
                <i class="las la-users-cog menu-icon"></i>
                <span class="menu-title">Drivers</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ menuActive('transport.driver.*', 3) }}" id="driver-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link"
                            href="{{route('transport.driver.add')}}">Add</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link"
                            href="{{route('transport.driver.index')}}">List</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item {{ menuActive('transport.vehicle.*') }}">
            <a class="nav-link" data-toggle="collapse" href="#vehicle-menu"
                aria-expanded="{{ menuActive('transport.vehicle.*', 2) }}" aria-controls="vehicle-menu">
                <i class="las la-truck menu-icon"></i>
                <span class="menu-title">Vehicle</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ menuActive('transport.vehicle.*', 3) }}" id="vehicle-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link"
                            href="{{route('transport.vehicle.add')}}">Add</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link"
                            href="{{route('transport.vehicle.index')}}">List</a></li>
                </ul>
            </div>
        </li>

         <li class="nav-item {{ menuActive('transport.route.*') }}">
            <a class="nav-link" href="{{ route('transport.route.index') }}">
                <i class="las la-route menu-icon" aria-hidden="true"></i>
                <span class="menu-title">Routes List</span>
            </a>
        </li>
         <li class="nav-item {{ menuActive('transport.profile') }}">
            <a class="nav-link" href="{{ route('transport.profile.index') }}">
                <i class="las la-cog menu-icon" aria-hidden="true"></i>
                <span class="menu-title">Profile Settings</span>
            </a>
        </li>
    </ul>

</nav>
