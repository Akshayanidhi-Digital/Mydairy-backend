<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="{{ route('home') }}"><img src="{{ asset(appLogoUrl('logo.svg')) }}"
                class="mr-2" alt="logo" /></a>
        <a class="navbar-brand brand-logo-mini" href="{{ route('home') }}"><img
                src="{{ asset(appLogoUrl('logo-mini.svg')) }}" alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
            {{-- <li class="nav-item">
                <select class="form-select form-select-sm" id="languageChange">
                    <option value="en" @if (app()->getLocale() == 'en') selected @endif>@lang('lang.English')</option>
                    <option value="hi" @if (app()->getLocale() == 'hi') selected @endif>@lang('lang.Hindi')</option>
                </select>
            </li> --}}
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" onclick="hideBadge()" id="notificationDropdown"
                    href="javascript:void(0)" data-toggle="dropdown">
                    <i class="las la-bell mx-0"></i>
                    <span class="count" id="countNoti" style="display: none"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                    aria-labelledby="notificationDropdown">
                    <div class="d-flex justify-content-between align-items-center mr-3">
                        <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                        <a href="" id="viewAllnotification" class="btn btn-sm  btn-primary">View All</a>
                    </div>
                    <div id="messageList">
                    </div>
                </div>
            </li>
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                    <img src="{{ asset(getProfileImage(auth()->user()->transporter_id)) }}" alt="profile" />
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a href="{{ route('transport.profile.index') }}" class="dropdown-item">
                        <i class="las la-user text-primary"></i>
                        @lang('constants.Profile')
                    </a>
                    <a href="{{ route('transport.profile.password.update') }}" class="dropdown-item">
                        <i class="las la-cog text-primary"></i>
                        Update Password
                    </a>
                    <a class="dropdown-item" href="{{ route('transport.logout') }}">
                        <i class="las la-sign-out-alt text-primary"></i>
                        @lang('constants.Logout')
                    </a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>
