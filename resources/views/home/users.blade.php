@extends('master')
@include('navbar')
@section('content')
<div class="container">
    @foreach($usernames as $username) 
    <br><br><br><br>
    <h2>{{$username->username}}</h2>
    <a href="/home/data/{{$username->id}}" class="btn btn-primary">Mau liat dong</a>
    @endforeach
</div>
@endsection