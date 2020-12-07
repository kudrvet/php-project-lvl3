<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-param" content="_token" />
    <link href={{ asset('css/app.css') }} rel="stylesheet">
    <script src="{{ asset('/js/app.js') }}"></script>
</head>
<body>

<div class="container">
    @include('flash::message')
</div>


<nav class="navbar navbar-dark bg-red mb-5">
    <div class="nav-item">
        <a href={{route('homepage')}}>Homepage |</a>
        <a href={{route('domains.index')}}>Domains</a>
    </div>

</nav>

<div class="container">
    @yield('content')
</div>

</body>
</html>