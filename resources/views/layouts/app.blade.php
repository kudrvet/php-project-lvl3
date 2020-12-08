<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-param" content="_token" />
    <link href={{ secure_asset('css/app.css') }} rel="stylesheet">
    <script src="{{ secure_asset('/js/app.js') }}"></script>
</head>
<body class="d-flex flex-column">

<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand" href={{route('homepage')}}>Analyzer</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href={{route('homepage')}}>Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href={{route('domains.index')}}>Domains</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
    @yield('flash')
<main class="flex-grow-1">
    @yield('content')
</main>
</body>
</html>