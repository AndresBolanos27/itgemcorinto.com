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
        
        <!-- Estilos adicionales para mejorar responsividad -->
        <link href="{{ asset('css/responsive-nav.css') }}" rel="stylesheet">
        <style>
            [x-cloak] { display: none !important; }
            
            @media (max-width: 640px) {
                .container-fluid {
                    padding-left: 0.5rem;
                    padding-right: 0.5rem;
                }
                
                table {
                    display: block;
                    overflow-x: auto;
                    white-space: nowrap;
                }
                
                .responsive-card {
                    width: 100%;
                }
            }
            
            /* Estilos para tablas responsivas */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            /* Mejoras para formularios en móviles */
            @media (max-width: 768px) {
                .form-grid {
                    grid-template-columns: 1fr !important;
                }
                
                .responsive-padding {
                    padding-left: 0.75rem !important;
                    padding-right: 0.75rem !important;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8 responsive-padding">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot ?? '' }}
            </main>
        </div>

        @stack('scripts')
    </body>
</html>
