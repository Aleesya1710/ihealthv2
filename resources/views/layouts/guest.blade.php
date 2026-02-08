<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-[#104F5D] flex items-center justify-center">
            
            <div class="flex flex-row w-full h-[600px] rounded-lg overflow-hidden shadow-lg">
                <div class="w-1/2 flex items-center justify-center">
                    <a href="/">
                        <x-application-logo class="w-40 h-40 fill-current text-gray-300" />
                    </a>
                </div>
    
                <div class="w-1/2 bg-white flex items-center justify-center p-8">
                    <div class="w-full max-w-md space-y-4">
                        @include('partials.notifications')
                        {{ $slot }}
                    </div>
                </div>
            </div>
    
        </div>
    </body>
    
    
</html>
