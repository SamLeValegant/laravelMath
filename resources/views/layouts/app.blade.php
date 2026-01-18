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
            <div id="main-content" class="ml-0 transition-all">
                        <script>
                        // Drawer fermé par défaut et synchronisation de la marge
                        document.addEventListener('DOMContentLoaded', function () {
                            const drawer = document.getElementById('drawer');
                            const mainContent = document.getElementById('main-content');
                            const openBtn = document.getElementById('drawer-open');
                            const toggleBtn = document.getElementById('drawer-toggle');
                            function closeDrawer() {
                                drawer.style.transform = 'translateX(-100%)';
                                openBtn.style.display = 'block';
                                mainContent.style.marginLeft = '0';
                            }
                            function openDrawer() {
                                drawer.style.transform = 'translateX(0)';
                                openBtn.style.display = 'none';
                                mainContent.style.marginLeft = drawer.offsetWidth + 'px';
                            }
                            if (drawer && mainContent && openBtn && toggleBtn) {
                                // Drawer fermé par défaut au chargement
                                closeDrawer();
                                // Responsive : si on ouvre le drawer
                                openBtn.addEventListener('click', function() {
                                    openDrawer();
                                });
                                // Si on ferme le drawer
                                toggleBtn.addEventListener('click', function() {
                                    closeDrawer();
                                });
                                // Adapter la marge si la fenêtre change
                                window.addEventListener('resize', function() {
                                    if (drawer.style.transform === 'translateX(0)') {
                                        mainContent.style.marginLeft = drawer.offsetWidth + 'px';
                                    } else {
                                        mainContent.style.marginLeft = '0';
                                    }
                                });
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
