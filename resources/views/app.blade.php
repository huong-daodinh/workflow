<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="{{ url('assets/favicon_io/favicon-32x32.png') }}" type="image/x-icon" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <!-- <body class="font-sans antialiased"> -->

    <body class="main-section antialiased font-sans bg-[#fafafa]">
        @inertia
        @if (env('APP_IS_DOCKER') == 'true')
          {{-- <script src="/socket.io/socket.io.js"></script> --}}
        @else
          <script src="http://localhost:6001/socket.io/socket.io.js"></script>
        @endif
    </body>
</html>
