<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item {{ menuActive('admin.dashboard') }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="mdi mdi-view-dashboard menu-icon icon_cost"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.user.*') }}">
            <a class="nav-link" href="{{ route('admin.user.list') }}">
                <i class="mdi mdi-account-group menu-icon icon_cost"></i>
                <span class="menu-title">User Management</span>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.payments.*') }}">
            <a class="nav-link" href="{{ route('admin.payments.list') }}">
                <i class="mdi mdi-cash-sync menu-icon icon_cost"></i>
                <span class="menu-title">Payments</span>
            </a>
        </li>
        <li class="nav-item {{ menuActive('admin.groups.*') }}">
            <a class="nav-link" data-toggle="collapse" href="#gp"
                aria-expanded="{{ menuActive('admin.plans.*', 2) }}" aria-controls="ui-basic">
                <i class="mdi mdi-list-box menu-icon icon_cost"></i>
                <span class="menu-title">Product Groups</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ menuActive('admin.groups.*', 3) }}" id="gp">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.groups.list') }}">List</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item {{ menuActive('admin.brands.*') }}">
            <a class="nav-link" data-toggle="collapse" href="#bnd"
                aria-expanded="{{ menuActive('admin.plans.*', 2) }}" aria-controls="ui-basic">
                <i class="mdi mdi-list-box menu-icon icon_cost"></i>
                <span class="menu-title">Product Brands</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ menuActive('admin.brands.*', 3) }}" id="bnd">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.brands.list') }}">List</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item {{ menuActive('admin.products.*') }}">
            <a class="nav-link" data-toggle="collapse" href="#pro"
                aria-expanded="{{ menuActive('admin.plans.*', 2) }}" aria-controls="ui-basic">
                <i class="mdi mdi-list-box menu-icon icon_cost"></i>
                <span class="menu-title">In House Products</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ menuActive('admin.products.*', 3) }}" id="pro">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.products.list') }}">List</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item {{ menuActive('admin.plans.*') }}">
            <a class="nav-link" data-toggle="collapse" href="#plans"
                aria-expanded="{{ menuActive('admin.plans.*', 2) }}" aria-controls="ui-basic">
                <i class="mdi mdi-list-box menu-icon icon_cost"></i>
                <span class="menu-title">Plans</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ menuActive('admin.plans.*', 3) }}" id="plans">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.plans.create') }}">Create</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.plans.list') }}">List</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item {{ menuActive('admin.apphelp.*') }}">
            <a class="nav-link" data-toggle="collapse" href="#apphelp"
                aria-expanded="{{ menuActive('admin.apphelp.*', 2) }}" aria-controls="ui-basic">
                <i class="mdi mdi-lifebuoy menu-icon icon_cost"></i>
                <span class="menu-title">App Helps</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ menuActive('admin.apphelp.*', 3) }}" id="apphelp">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.apphelp.create') }}">Create</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.apphelp.list') }}">List</a>
                    </li>
                </ul>
            </div>
        </li>

    </ul>
</nav>
