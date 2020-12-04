<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>

<div class="container">
    @include('flash::message')
</div>


<nav class="navbar navbar-dark bg-red">

{{ link_to_route('homepage',$title = 'Homepage', $parameters = [], $attributes = [])}}
{{ link_to_route('domains.index', $title = 'Domains', $parameters = [], $attributes = [])}}
</nav>


<div class="container">
    @yield('content')
</div>


</body>
</html>