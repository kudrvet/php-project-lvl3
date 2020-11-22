@extends('layouts.app')
@section('content')
<table class="table table-bordered">
    <thead>
    <tr>
        <th>id</th>
        <th>Name</th>
        <th>Created_at</th>
        <th>Updated_at</th>

    </tr>
    </thead>
    <tbody>
    @foreach($domains as $domain)
    <tr>
        <td>{{$domain->id}}</td>
        <td>{{ link_to_route('domains.show',$title = $domain->name, $parameters = ['id' => $domain->id], $attributes = [])}}</td>
        <td>{{$domain->created_at}}</td>
        <td>{{$domain->updated_at}}</td>
    </tr>
    @endforeach
    </tbody>
</table>
@endsection