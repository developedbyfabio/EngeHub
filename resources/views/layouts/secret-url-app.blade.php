<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Não forçar light: com light o mapa fica preto; o conteúdo principal usa color-scheme: dark no CSS -->

    <title>{{ config('app.name', 'EngeHub - Intranet') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('media/favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('media/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('media/favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased custom-wallpaper-bg">
    <div class="min-h-screen">
        <!-- Header Simplificado -->
        <nav class="bg-black border-b border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url()->current() }}" class="flex items-center">
                            <img src="/media/logo.png" alt="EngeHub" class="h-6 w-auto max-h-6" style="max-width: 120px;">
                        </a>
                    </div>
                    <div class="flex items-center">
                        @if(isset($systemUser))
                            <span class="text-white text-sm">
                                <i class="fas fa-user mr-2"></i>{{ $systemUser->name }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content: wrapper com color-scheme dark para renderização igual ao tema escuro do navegador -->
        <main class="secret-url-main-content" style="padding-top: 64px;">
            @yield('content')
        </main>
    </div>

    <!-- Toast Notification System -->
    @include('components.toast-notification')

    <style>
        /* Conteúdo principal: usar color-scheme dark para que a renderização seja igual à do tema escuro
           (quando o navegador está em tema claro, o mapa e o card ficam pretos; com dark fica correto) */
        .secret-url-main-content { color-scheme: dark !important; }
        .secret-url-main-content .bg-white { background-color: #ffffff !important; }
        body { color: #111827; background-color: transparent; }
        
        /* Background wallpaper personalizado */
        .custom-wallpaper-bg {
            background-image: url('/media/Wallpaper 1920x1080.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        
        /* Aplicar fundo semi-transparente apenas em containers de conteúdo */
        .bg-white {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(1px);
        }
        
        /* Garantir texto escuro no conteúdo principal (não herdar do tema do navegador) */
        main, main * {
            color: inherit;
        }
        main .text-gray-900 { color: #111827 !important; }
        main .text-gray-800 { color: #1f2937 !important; }
        main .text-gray-700 { color: #374151 !important; }
        main .text-gray-600 { color: #4b5563 !important; }
        main .text-gray-500 { color: #6b7280 !important; }
        
        /* Cabeçalho fixo — abaixo de modais (z-50+) */
        nav {
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            z-index: 40;
            background-color: #000000 !important;
            border-bottom: 1px solid #333333;
        }
    </style>
</body>
</html>




