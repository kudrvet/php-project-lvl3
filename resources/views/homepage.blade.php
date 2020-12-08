@extends('layouts.app')

@section('flash')
    <div class="bg-dark">
        @include('flash::message')
    </div>
@endsection

@section('content')

    <div class="jumbotron jumbotron-fluid bg-dark">
        <div class="container-lg">
            <div class="row">
                <div class="col-12 col-md-10 col-lg-8 mx-auto text-white">
                    <h1 class="display-3">Page Analyzer</h1>
                    <p class="lead">Check web pages for free</p>
                    {{Form::open(['route' => 'domains.store'])}}
                    <input type="text" name="domain[name]" value="" class="form-control form-control-lg"
                           placeholder="https://www.example.com">
                    <button type="submit" class="btn btn-lg btn-outline-primary mt-3 px-5 text-uppercase">Check</button>
                    {{Form::close()}}
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection