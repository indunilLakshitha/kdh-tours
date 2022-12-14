<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title inertia>{{ config('Bizeyes', 'KDH Tours') }}</title>
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        @routes
        @vite('resources/js/app.js')
        @vite(['resources/sass/app.scss'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">

        <div class="container">
            @inertia
        </div>

    </body>
</html>
