<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title')</title>

    <meta name="description" content="@yield('meta-description')">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fandom-list.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">

</head>
<body>

@include('layouts.header')

<div class="content">
    @yield('content')
</div>

</body>
</html>
