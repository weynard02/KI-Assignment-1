@extends('master')
@include('navbar')
@section('content')
<div class="container">
    @foreach($usernames as $username) 
    <br><br><br><br>
    <h2>{{$username}}</h2>
    @endforeach
</div>
@endsection