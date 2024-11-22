<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item {{ menuActive('user.dashboard') }}">
            <a class="nav-link" href="{{ route('user.dashboard') }}">
                <i class="fa fa-tachometer menu-icon"></i>
                <span class="menu-title">@lang('constants.dashboard') </span>
            </a>
        </li>
        <li
            class="nav-item {{ menuActive('user.Costumers.*') }} {{ menuActive('user.farmers.*') }} {{ menuActive('user.buyers.*') }} {{ menuActive('user.childUser.*') }}">
            <a class="nav-link" data-toggle="collapse" href="#cstumers-menu"
                aria-expanded="{{ menuActive('user.Costumers.*', 2) }} {{ menuActive('user.farmers.*', 2) }} {{ menuActive('user.buyers.*', 2) }} {{ menuActive('user.childUser.*', 2) }}"
                aria-controls="cstumers-menu">
                <i class="fa fa-users menu-icon"></i>
                <span class="menu-title">@lang('constants.customers')</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ menuActive('user.Costumers.*', 3) }} {{ menuActive('user.farmers.*', 3) }} {{ menuActive('user.buyers.*', 3) }} {{ menuActive('user.childUser.*', 3) }}"
                id="cstumers-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.farmers.list') }}">Dairy Farmers</a>
                    </li>
                    <li class="nav-item">
                        {{-- <a class="nav-link"
                            href="{{ route('user.buyers.list') }}">@lang('constants.Buyers')</a> --}}
                    </li>
                    @if (!auth()->user()->is_single())
                        @php
                            $roles = get_dairy_roles();
                        @endphp
                        @foreach ($roles as $role)
                            @if ($role->short_name == 'MCC')
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('user.childUser.list', $role->short_name) }}">Milk Collection
                                        Center</a>
                                </li>
                            @elseif($role->short_name == 'BMC')
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('user.childUser.list', $role->short_name) }}">Bulk Milk
                                        Collection Unit</a>
                                </li>
                            @elseif($role->short_name == 'DCS')
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('user.childUser.list', $role->short_name) }}">Doodh Collection
                                        Society</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('user.childUser.list', $role->short_name) }}">{{ $role->short_name }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
        </li>
        <li class="nav-item {{ menuActive('user.Milkbuy.index') }}">
            <a class="nav-link" onclick="milkBuyEntry()">
                <i class="fa fa-th-large menu-icon"></i>
                <span class="menu-title">@lang('constants.milkbuy')</span>
            </a>
        </li>
        <li class="nav-item {{ menuActive('user.MilkSell.index') }}">
            <a class="nav-link" onclick="milkSellEntry()">
                <i class="fa fa-th-large menu-icon"></i>
                <span class="menu-title">@lang('constants.milksell')</span>
            </a>
        </li>
        <li class="nav-item {{ menuActive('user.rateCharts.*') }}">
            <a class="nav-link" href="{{ route('user.rateCharts.list') }}">
                <i class="fa fa-tachometer menu-icon"></i>
                <span class="menu-title">@lang('constants.MilkRatechart') </span>
            </a>
        </li>
        <li class="nav-item {{ menuActive('user.products.*') }}">
            <a class="nav-link" data-toggle="collapse" href="#products-menu"
                aria-expanded="{{ menuActive('user.products.*', 2) }}" aria-controls="products-menu">
                <i class="fa fa-th-large menu-icon"></i>
                <span class="menu-title">@lang('constants.Products')</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ menuActive('user.products.*', 3) }}" id="products-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link"
                            href="{{ route('user.products.groups.list') }}">@lang('constants.Groups')</a></li>
                    <li class="nav-item"> <a class="nav-link"
                            href="{{ route('user.products.brands.list') }}">@lang('constants.Brands')</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link"
                            href="{{ route('user.products.list') }}">@lang('constants.List')</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item {{ menuActive('user.records.*') }}">
            <a class="nav-link" data-toggle="collapse" href="#records-menu"
                aria-expanded="{{ menuActive('user.records.*', 2) }}" aria-controls="records-menu">
                <i class="fa fa-file-text menu-icon"></i>
                <span class="menu-title">@lang('lang.Milk Reports')</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ menuActive('user.records.*', 3) }}" id="records-menu">
                <ul class="nav flex-column sub-menu">
                    @if (auth()->user()->is_subdairy())
                        <li class="nav-item"> <a href="{{ route('user.records.milk.request') }}"
                                class="nav-link">@lang('lang.Milk Request')</a>
                        </li>
                    @endif
                    <li class="nav-item"> <a href="{{ route('user.records.milk.buy') }}"
                            class="nav-link">@lang('lang.Procurement')</a>
                    </li>
                    <li class="nav-item"> <a href="{{ route('user.records.milk.sell') }}"
                            class="nav-link">@lang('lang.sale')</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item {{ menuActive('user.shopping.*') }}">
            <a class="nav-link" data-toggle="collapse" href="#shopping-menu"
                aria-expanded="{{ menuActive('user.shopping.*', 2) }}" aria-controls="shopping-menu">
                <i class="fa fa-shopping-bag menu-icon"></i>
                <span class="menu-title">@lang('constants.Shopping')</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ menuActive('user.shopping.*', 3) }}" id="shopping-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a href="{{ route('user.shopping.cart') }}"
                            class="nav-link">@lang('constants.Cart')</a>
                    <li class="nav-item"> <a href="{{ route('user.shopping.order') }}"
                            class="nav-link">@lang('constants.Orders')</a>
                    </li>
                    <li class="nav-item"> <a href="{{ route('user.shopping.list') }}"
                            class="nav-link">@lang('constants.All Products')</a>
                    </li>
                </ul>
            </div>
        </li>
        @if (!auth()->user()->is_single() && !auth()->user()->is_subdairy())
            <li class="nav-item {{ menuActive('user.masters.*') }}">
                <a class="nav-link" data-toggle="collapse" href="#master-menu"
                    aria-expanded="{{ menuActive('user.masters.*', 2) }}" aria-controls="products-menu">
                    <i class="fa fa-th-list menu-icon"></i>
                    <span class="menu-title">@lang('lang.Master')</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ menuActive('user.masters.*', 3) }}" id="master-menu">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('user.masters.roles.list') }}">
                                @lang('lang.Roles')
                            </a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('user.masters.routes.list') }}">
                                @lang('lang.Routes')
                            </a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('user.masters.transport.list') }}">
                                @lang('lang.Transporters')
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif
        @if (!auth()->user()->is_subdairy())
            <li class="nav-item {{ menuActive('user.plans.*') }}">
                <a class="nav-link" href="{{ route('user.plans.list') }}">
                    <i class="fa fa-money  menu-icon" aria-hidden="true"></i>
                    <span class="menu-title">@lang('constants.Billing History')</span>
                </a>
            </li>
        @endif
        <li class="nav-item {{ menuActive('user.settings') }}">
            <a class="nav-link" href="{{ route('user.settings') }}">
                <i class="fa fa-cog  fa-fw menu-icon" aria-hidden="true"></i>
                <span class="menu-title">@lang('constants.Settings')</span>
            </a>
        </li>
    </ul>

</nav>
