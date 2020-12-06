<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="{{ secure_asset(asset('css/app.css')) }}" rel="stylesheet">
    <script src="{{ secure_asset(asset('js/app.js')) }}"></script>
</head>
<body>

<div class="container">
    @include('flash::message')
</div>


<nav class="navbar navbar-dark bg-red ">
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