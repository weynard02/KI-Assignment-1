@extends('master')
@include('navbar')
@section('content')
<div class="container">
    <h1 class="text-center display-5 fw-bold" style="margin-top: 100px">Hi {{Auth::user()->username}}, this is your profile!</h1>
</div>
@endsection