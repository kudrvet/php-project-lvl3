@extends('layouts.app')
@section('content')
<table class="table table-bordered">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Last Check</th>
        <th>Status Code</th>

    </tr>
    </thead>
    <tbody>
    @foreach($domains as $domain)
    <tr>
        <td>{{$domain->domain_id}}</td>
        <td>{{ link_to_route('domains.show',$title = $domain->name, $parameters = ['id' => $domain->domain_id], $attributes = [])}}</td>
        <td>{{$domain->last_post_created_at}}</td>
        <td>{{$domain->status_code}}</td>
    </tr>
    @endforeach
    </tbody>
</table>
@endsection