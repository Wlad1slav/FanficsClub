<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ $fanfic->title }} :: Редагувати</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/my-profile.css') }}">

</head>
<body>

@include('layouts.header')

<div id="page">
    @include('fanfic-edit.menu')
    <div class="content full-width">
        @yield('content')
    </div>
</div>

@include('layouts.footer')

</body>
</html>
