<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ \Bambamboole\MyCms\Facades\MyCms::getGeneralSettings()->site_name }} | @yield('title', 'Home')</title>

    <meta name="description" content="Manuel Christlieb | Technical Lead - Xentral Platform Team">

    <meta property="og:site_name" content="{{ \Bambamboole\MyCms\Facades\MyCms::getGeneralSettings()->site_name }}">
    <meta property="og:locale" content="{{ config('app.locale') }}">
    <meta property="og:description"
          content="Manuel Christlieb | Technical Lead - Xentral Platform Team.">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:image" content="https://christlieb.eu/images/logo.png">
    <script type='application/ld+json'>
    {
        "@context":"http:\/\/schema.org",
        "@type":"WebSite",
        "@id":"#website",
        "url":"https:\/\/christlieb.eu\/",
        "name":"christlieb.eu",
        "alternateName":"A blog on modern PHP development and other tech related stuff"
    }

    </script>
    <script type='application/ld+json'>
    {
        "@context":"http:\/\/schema.org",
        "@type":"Person",
        "sameAs":["https:\/\/twitter.com\/bambamboole1"],
        "@id":"#person",
        "name":"Manuel Christlieb"
    }

    </script>
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
