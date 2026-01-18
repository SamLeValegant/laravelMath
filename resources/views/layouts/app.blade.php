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

        <div class="min-h-screen bg-gray-100">
            @auth
                @include('layouts.drawer')
            @endauth
            <div id="main-content" class="ml-0 @auth md:ml-64 @endauth transition-all">
                        <script>
                        // Synchroniser la marge du contenu principal avec l'état du drawer
                        document.addEventListener('DOMContentLoaded', function () {
                            const drawer = document.getElementById('drawer');
                            const mainContent = document.getElementById('main-content');
                            const openBtn = document.getElementById('drawer-open');
                            if (drawer && mainContent && openBtn) {
                                function updateMargin() {
                                    if (drawer.style.transform === 'translateX(-100%)') {
                                        mainContent.classList.remove('md:ml-64');
                                    } else {
                                        mainContent.classList.add('md:ml-64');
                                    }
                                }
                                // Sur ouverture/fermeture du drawer
                                drawer.addEventListener('transitionend', updateMargin);
                                openBtn.addEventListener('click', function() {
                                    setTimeout(updateMargin, 210);
                                });
                                // Pour le bouton de fermeture
                                const toggleBtn = document.getElementById('drawer-toggle');
                                if (toggleBtn) {
                                    toggleBtn.addEventListener('click', function() {
                                        setTimeout(updateMargin, 210);
                                    });
                                }
                            }
                        });
                        </script>
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
