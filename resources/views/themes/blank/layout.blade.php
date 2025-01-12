<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('seo')
</head>
<body>
<header>
    <ul>
        @foreach(\Bambamboole\MyCms\Facades\MyCms::getMenuItems('header') as $item)
            <li><a href="{{$item->url}}">>{{$item->title}}</a></li>
        @endforeach
    </ul>
</header>
<main>
    @yield('content')
</main>
<footer>
    <ul>
        @foreach(\Bambamboole\MyCms\Facades\MyCms::getMenuItems('footer') as $item)
            <li><a href="{{$item->url}}">>{{$item->title}}</a></li>
        @endforeach
    </ul>
</footer>
</body>
</html>
