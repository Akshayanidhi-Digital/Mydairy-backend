<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset="utf-8">
    <title> @lang('constants.Mydairy') @isset($title)
            - {{ $title }}
        @endisset </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href='{{ asset('assets/main/plugins/boxicons/boxicons.js') }}' rel='stylesheet'>
    <link href='{{ asset('assets/main/plugins/boxicons/boxicons.min.css') }}' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        body {
            background: #CEE5F6;
            font-family: Roboto;
        }

        .hero_bg {
            filter: brightness(45%);
            z-index: -1;
        }

        .front_layer img {
            height: 15rem;
            width: 30rem;
        }

        .border_profile {
            border: 2px solid #0066B7;
        }

    </style>
    @laravelPWA
    @yield('styles')
</head>

<body>
    @include('main.partials.header')
    @yield('content')
    @include('main.partials.footer')
</body>

</html>
