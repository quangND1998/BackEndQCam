@extends('callcenter::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('callcenter.name') !!}</p>
@endsection
