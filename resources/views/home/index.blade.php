@extends('master')
@include('navbar')
@php
    $homeController = app('App\Http\Controllers\HomeController');
@endphp
@section('content')
<div class="container">
    <h1 class="text-center display-5 fw-bold" style="margin-top: 100px">Hi {{ Auth::user()->username }}, this is your profile!</h1>
    <h3>AES</h3>
    @foreach ($aess as $aes)
        Full Name: {{ $homeController->AESdecrypt($aes->fullname, 0) }} <br>
        ID-Card: <a href="/download/aes/id_card/{{$aes->id}}" class="btn btn-dark btn-sm">Download</a> <br>
        Document: <a href="/download/aes/document/{{$aes->id}}" class="btn btn-dark btn-sm">Download</a> <br>
        Video: <a href="/download/aes/video/{{$aes->id}}" class="btn btn-dark btn-sm">Download</a> <br>
    @endforeach

    <h3>Rc4</h3>
    @foreach ($rc4s as $rc4)
        Full Name: {{ $homeController->Rc4decrypt($rc4->fullname, $rc4->key, 0) }} <br>
    @endforeach

    <h3>Des</h3>
    @foreach ($des as $des)
        Full Name: {{ $homeController->Desdecrypt($des->fullname, $des->key, $des->iv, 0) }} <br>
    @endforeach
</div>
@endsection
