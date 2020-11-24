@extends('layouts.app')

@section('content')
    {{Form::open(['route' => 'domains.store'])}}
    {{Form::text('domain[name]')}}
    {{Form::submit('Check!')}}
    {{Form::close()}}

@endsection