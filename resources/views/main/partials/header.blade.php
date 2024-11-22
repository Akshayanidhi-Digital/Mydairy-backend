<header class="text-gray-600 body-font bg-white sticky top-0 z-[999]">
    <div class="justify-between w-full flex py-2 xl:py-4 xl:px-10 md:py-3 md:px-8 px-3 md:flex-row items-center">
        <a
            class="flex title-font cursor-pointer font-medium h-12 w-12 lg:w-20 lg:h-20 items-center text-gray-900 mb-4 md:mb-0">
            <img src="{{ asset('assets/main/logo.png') }}" alt="logo" class="h-full w-full" />
        </a>
        <nav
            class="md:ml-auto md:mr-auto  flex-wrap items-center hidden lg:flex text-base justify-center cursor-pointer">
            <a class="mr-5 text-[#0066B7] font-medium text-xl">Home</a>
            <a class="mr-5 hover:text-[#0066B7] font-medium text-xl">About Us</a>
            <a class="mr-5 hover:text-[#0066B7] font-medium text-xl">Pages</a>
            <a class="mr-5 hover:text-[#0066B7] font-medium text-xl">Blog</a>
            <a class="mr-5 hover:text-[#0066B7] font-medium text-xl">Contact</a>
        </nav>
        @if (auth()->check() || auth('transport')->check())
            <a href="{{  getDashbordRoute() }}"
                class="flex gap-2 items-center cursor-pointer">
                <i class='bx bx-user text-3xl'></i>
                <p class="cursor-pointer">{{  getUserName() }}</p>
            </a>
        @else
            <div id="loginmenubtn" class="flex gap-2 items-center cursor-pointer " data-dropdown-toggle="loginmenus">
                <i class='bx bx-user text-3xl'></i>
                <p class="hidden lg:flex">Login</p>
                <i class='bx hidden lg:block bx-chevron-down text-3xl'></i>
                <i class="bx bx-menu lg:hidden text-4xl"></i>
            </div>
            <div id="loginmenus"
                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="loginmenubtn">
                    {{-- <li>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Dashboard</a>
              </li> --}}
                    <li>
                        <a href="{{ route('login') }}"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Dairy
                            Login</a>
                    </li>
                    <li>
                        <a href="{{ route('transport.login') }}"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Transporter
                            Login</a>
                    </li>
                    {{-- <li>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Driver Login</a>
              </li> --}}
                </ul>
            </div>
        @endif
    </div>
</header>
