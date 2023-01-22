<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/img/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/dd.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <title>{{ env('APP_NAME') }} @yield('title')</title>
</head>
<body>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('assets/js/libs/coin-marquee.js') }}"></script>
    <script src="{{ asset('assets/js/libs/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/dd.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>$("body select").msDropDown();</script>
</body>
</html>