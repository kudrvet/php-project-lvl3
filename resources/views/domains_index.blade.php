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
        <td>{{$domain->id}}</td>
        <td> <a href ={{route('domains.show',['id' => $domain->id])}}>{{$domain->name}} </a></td>

        <td>{{$domain->last_post_created_at}}</td>
        <td>{{$domain->status_code}}</td>
    </tr>
    @endforeach
    </tbody>
</table>
@endsection