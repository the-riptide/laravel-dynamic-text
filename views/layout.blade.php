<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'The Riptide') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    @stack('head')

    {{-- Inter font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    @livewireStyles
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">

        <div class="divide-y divide-gray-200 bg-white shadow">
            {{-- Main Nav --}}
            <x-dyntext::menu.menu :items="$menuItems" />

            <!-- Page Header -->
            @if (View::hasSection('title'))
                <header class="text-2xl font-semibold">
                    <div class="max-w-6xl mx-auto px-4 lg:px-8 py-4">
                        <h3 class="text-2xl font-bold text-gray-900">
                            @yield('title')
                        </h3>
                    </div>
                </header>
            @endif
        </div>

        <!-- Page Content -->
        <main class="py-16 lg:py-12">
            <div class="max-w-6xl mx-auto px-4 lg:px-8">
                @yield('body')
            </div>
        </main>

    </div>

    @livewireScripts
</body>

</html>
