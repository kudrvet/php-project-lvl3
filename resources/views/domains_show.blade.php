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

    {{Form::open(['route' => ['domains.check',$domain->id]])}}
    {{Form::submit('Check!')}}
    {{Form::close()}}

    <table class="table table-bordered">
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
                <td>{{$domainCheck->h1}}</td>
                <td>{{$domainCheck->keywords}}</td>
                <td>{{$domainCheck->description}}</td>
                <td>{{$domainCheck->created_at}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection




