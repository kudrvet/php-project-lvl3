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
        <tr>
            <td>{{$domain->id}}</td>
            <td>{{$domain->name}}</td>
            <td>{{$domain->created_at}}</td>
            <td>{{$domain->updated_at}}</td>
        </tr>
        </tbody>
    </table>
@endsection




