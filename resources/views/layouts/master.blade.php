<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/css/app.css">

        <title>Account Management</title>
    </head>
    <body>
        <div id="root">
            @include ('layouts.nav')


            @yield('content')
        </div>
        <script src="/js/app.js"></script>
    </body>
</html>
