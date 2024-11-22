<nav class="navbar main-nav navbar-expand-lg px-2 px-sm-0 py-2 py-lg-0">
    <div class="container">
        <a class="navbar-brand" href="index.html"><img src="{{ asset('assets/main/images/logo.png') }}" alt="logo"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="ti-menu"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item  active">
                    <a class="nav-link" href="#">Home
                    </a>
                </li>
                <li class="nav-item @@contact">
                    <a class="nav-link" href="contact.html">Contact</a>
                </li>
                @auth
                    <li class="nav-item @@about">
                        <a class="nav-link" href="{{ route('login') }}">Dashboard</a>
                    </li>
                @else
                    <li class="nav-item @@about">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>
