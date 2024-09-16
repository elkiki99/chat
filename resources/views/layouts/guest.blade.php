<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="favicon.png" rel="icon" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900">
    <div
        class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0 dark:bg-gray-900 bg-gradient-to-b from-green-100 via-green-500 to-green-100">

        <div>
            <a wire:navigate href="/">
                <x-application-logo class="w-20 h-20 text-green-100 fill-current" />
            </a>
        </div>

        <div
            class="w-full mt-6 overflow-hidden rounded-lg shadow-lg bg-opacity-30 backdrop-blur-lg sm:max-w-md dark:bg-gray-800 sm:rounded-lg ">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
