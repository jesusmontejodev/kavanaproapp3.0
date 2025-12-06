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
        <div class="min-h-screen bg-gray-100 lg:flex">
            <!-- Sidebar para desktop, oculto en mobile -->
            <div class="hidden lg:block w-64 bg-white shadow-lg flex-shrink-0">
                @include('layouts.navigation')
            </div>

            <!-- Contenido principal -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Header con botón para mobile -->
                @isset($header)
                    <header class="bg-white shadow lg:shadow-none border-b border-gray-200">
                        <div class="flex items-center justify-between px-4 py-4 lg:px-6">
                            <button class="lg:hidden text-gray-500 hover:text-gray-700">
                                <!-- Icono de menú -->
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                            <div class="flex-1">
                                {{ $header }}
                            </div>
                        </div>
                    </header>
                @endisset

                <main class="flex-1 overflow-auto p-4 lg:p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
