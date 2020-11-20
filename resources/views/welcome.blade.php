<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <META NAME="robots" CONTENT="noindex,nofollow">
        <title>{{ config('app.name', 'Rental Calendar') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="{{ env('APP_ENV')=='local'?asset('css/app.css'):secure_asset('css/app.css') }}" rel="stylesheet">

    </head>
    <body>
        <div id="app">
            @guest
                <calendar-component user="guest"></calendar-component>
            @else
                <calendar-component user="{{ Auth::user()->name}}"></calendar-component>
            @endguest
        </div>

        <script src="{{ env('APP_ENV')=='local'?asset('js/app.js'):secure_asset('js/app.js') }}" defer></script>
    </body>
</html>
