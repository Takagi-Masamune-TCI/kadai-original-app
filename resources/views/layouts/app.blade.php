<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        @include("commons.error_messages")

        <div class="h-screen bg-gray-100 flex">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="py-12 px-10 grow min-w-[500px] h-screen overflow-y-auto bg-gray-100">
                @yield("contents")
            </main>
        </div>
    </body>
</html>
