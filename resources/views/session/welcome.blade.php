@extends('master')
@include('navbar')
@section('content')
    <div class="container">
        <h1 class="text-center display-1 fw-bold" style="margin-top: 100px">Welcome to Our Page</h1>
        <img src="{{ url('img/welcome.svg') }}" alt="" class="mx-auto d-block">
    </div>
@endsection