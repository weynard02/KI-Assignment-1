@extends('master')
@include('navbar')
@section('content')
<div class="container">
    <h1 class="text-center display-5 fw-bold" style="margin-top: 100px">Hi {{ Auth::user()->username }}, this is your profile!</h1>
    <h3>AES</h3>
    @foreach ($aess as $aes)
        Full Name: {{ app('App\Http\Controllers\HomeController')->AESdecrypt($aes->fullname, 0) }}
    @endforeach

    <h3>Rc4</h3>
    @foreach ($rc4s as $rc4)
        Full Name: {{ app('App\Http\Controllers\HomeController')->Rc4decrypt($rc4->fullname, $rc4->key, 0) }}
    @endforeach

    <h3>Des</h3>
    @foreach ($des as $des)
        Full Name: {{ app('App\Http\Controllers\HomeController')->Desdecrypt($des->fullname, $des->key, $des->iv, 0) }}
    @endforeach
</div>
@endsection
