<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    @include('flash::message')
</div>

<!-- If using flash()->important() or flash()->overlay(), you'll need to pull in the JS for Twitter Bootstrap. -->
<script src="//code.jquery.com/jquery.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script>
    $('#flash-overlay-modal').modal();
</script>

<nav class="navbar navbar-dark bg-red">

{{ link_to_route('homepage',$title = 'Homepage', $parameters = [], $attributes = [])}}
{{ link_to_route('domains.index', $title = 'Domains', $parameters = [], $attributes = [])}}
</nav>


<div class="container">
    @yield('content')
</div>


</body>
</html>