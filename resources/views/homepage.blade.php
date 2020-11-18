

{{$errors}}

{{Form::open(['route' => 'domains.store'])}}
    {{Form::text('domain[name]')}}
    {{Form::submit('Check!')}}
{{Form::close()}}

{{ link_to_route('domains.index', $title = 'Domains', $parameters = [], $attributes = [])}}