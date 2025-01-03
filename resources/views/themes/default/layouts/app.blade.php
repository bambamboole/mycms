<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('seo')
    @vite('resources/css/app.css', 'vendor/mycms')
</head>
<body class="font-sans">
<div class="container mx-auto relative pb-6 px-2">
    @include('mycms::themes.default.partials.header')

    @yield('content')

    @include('mycms::themes.default.partials.footer')
</div>
</body>
</html>
