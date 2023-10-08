@extends('master')
@include('navbar')
@php
    $homeController = app('App\Http\Controllers\HomeController');
@endphp
@section('content')
<div class="container">
    <h1 class="text-center display-5 fw-bold" style="margin-top: 100px">Edit Your Profile</h1>
    <form action="/home" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="form-group mb-3" style="margin-top: 40px">
            <label class="fw-bold" style="font-size: 20px;">Full Name</label>
            <input type="text" class="form-control" name="fullname" placeholder="Enter your Full Name" value="{{$homeController->AESdecrypt($aess->first()->fullname, 0)}}">
            @error('fullname')
            <div class="alert alert-danger fs-6 text">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group mb-3" style="margin-top: 40px">
            <label class="fw-bold" style="font-size: 20px;">ID Card</label>
            <input type="file" class="form-control" name="id_card">
            @error('id_card')
            <div class="alert alert-danger fs-6 text">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group mb-3" style="margin-top: 40px">
            <label class="fw-bold" style="font-size: 20px;">Document</label>
            <input type="file" class="form-control" name="document">
            @error('document')
            <div class="alert alert-danger fs-6 text">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group mb-3" style="margin-top: 40px">
            <label class="fw-bold" style="font-size: 20px;">Video</label>
            <input type="file" class="form-control" name="video">
            @error('video')
            <div class="alert alert-danger fs-6 text">{{ $message }}</div>
            @enderror
        </div>
        <button class="btn btn-dark mt-5" type="submit">Replace</button>
    </form>
</div>
@endsection