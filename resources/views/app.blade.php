<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <script lang="javascript">

//   ███████╗██╗    ██╗ █████╗ ██████╗ ██████╗ ██╗██╗   ██╗███╗   ███╗
//   ██╔════╝██║    ██║██╔══██╗██╔══██╗██╔══██╗██║██║   ██║████╗ ████║
//   ███████╗██║ █╗ ██║███████║██████╔╝██████╔╝██║██║   ██║██╔████╔██║
//   ╚════██║██║███╗██║██╔══██║██╔═══╝ ██╔═══╝ ██║██║   ██║██║╚██╔╝██║
//   ███████║╚███╔███╔╝██║  ██║██║     ██║     ██║╚██████╔╝██║ ╚═╝ ██║
//   ╚══════╝ ╚══╝╚══╝ ╚═╝  ╚═╝╚═╝     ╚═╝     ╚═╝ ╚═════╝ ╚═╝     ╚═╝

        </script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=0.9, user-scalable=no">
        <link rel="shortcut icon" href="{{ asset('assets/img/logo.png') }}" type="image/x-icon">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="{{ asset('assets/css/dd.css') }}">
        <script src="{{ asset('assets/js/libs/dd.min.js') }}"></script>

        {{-- <link rel="stylesheet" href="/build/assets/app-c71cd0e3.css">
        <link rel="stylesheet" href="/build/assets/app-d84e6f18.css">
        <script src="/build/assets/app-2099dc31.js" defer></script> --}}
        @vite('resources/css/app.css')
        <title>{{ env('APP_NAME') }}</title>
    </head>
    <body>

        <div id="app"></div>

        {{-- <script src="{{ asset('assets/js/libs/coin-marquee.js') }}"></script> --}}
        @vite('resources/js/app.js')
    </body>
</html>
