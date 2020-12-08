@extends('layouts.app')

@section('flash')
    @include('flash::message')
@endsection

@section('content')

    <div class="container-lg">
        <h1 class="mt-5 mb-3 ml-3">Domains</h1>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Last Check</th>
                <th>Status Code</th>

            </tr>
            </thead>
            <tbody>
            @foreach($domains as $key => $domain)
                <tr>
                    <td>{{$domain->id}}</td>
                    <td><a href={{route('domains.show',['id' => $domain->id])}}>{{$domain->name}} </a></td>

                    <td>{{ $lastChecks[$domain->id]->created_at ?? '' }}</td>
                    <td>{{ $lastChecks[$domain->id]->status_code ?? ''}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection