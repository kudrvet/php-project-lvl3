@extends('layouts.app')

@section('content')
    <h1 class = 'mt-5 mb-3'>Site : {{$domain->name}} </h1>
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

    <h2 class="mt-5 mb-3">Checks</h2>
    {{Form::open(['route' => ['domains.check',$domain->id]])}}
    {{Form::submit('Check!')}}
    {{Form::close()}}

    <table class="table table-bordered table-hover mt-5">
        <thead>
        <tr>
            <th>id</th>
            <th>Status Code</th>
            <th>h1</th>
            <th>Keywords</th>
            <th>Description</th>
            <th>Created_at</th>

        </tr>
        </thead>
        <tbody>
        @foreach($domainsChecks as $domainCheck)
            <tr>
                <td>{{$domainCheck->id}}</td>
                <td>{{$domainCheck->status_code}}</td>
                <td>{{Str::limit($domainCheck->h1,30)}}</td>
                <td>{{Str::limit($domainCheck->keywords,30)}}</td>
                <td >{{Str::limit($domainCheck->description,30)}}</td>
                <td>{{$domainCheck->created_at}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection




