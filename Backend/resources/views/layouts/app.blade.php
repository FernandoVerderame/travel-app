<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | @yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css' integrity='sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==' crossorigin='anonymous'/>

    <style>
        body {
            visibility: hidden;
        }
    </style>

    @yield('cdns')

    <!-- Usando Vite -->
    @vite(['resources/js/app.js'])
</head>

<body class="bg-light">
    <div id="app">

        @include('includes.layouts.navbar')

        <main class="container main-cont py-4">

            {{-- Alerts --}}
            @include('includes.alerts')

            @yield('content')
        </main>

    </div>

    {{-- Modal --}}
    @include('includes.delete-modal')
    @include('includes.description-modal')

    @yield('scripts')
</body>

</html>
