@extends('master')
@include('navbar')
@section('content')
<div class="container">
    <h1 class="text-center display-5 fw-bold" style="margin-top: 100px">Complete Your Profile</h1>
    <form action="/home" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group mb-3" style="margin-top: 40px">
            <label class="fw-bold" style="font-size: 20px;">Full Name</label>
            <input type="text" class="form-control" name="fullname" placeholder="Enter your Full Name" value="{{old('fullname')}}">
            @error('fullname')
            <div class="alert alert-danger fs-6 text">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group mb-3" style="margin-top: 40px">
            <label class="fw-bold" style="font-size: 20px;">ID Card</label>
            <input type="file" class="form-control" name="id_card" value="{{old('id_card')}}">
            @error('id_card')
            <div class="alert alert-danger fs-6 text">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group mb-3" style="margin-top: 40px">
            <label class="fw-bold" style="font-size: 20px;">Document</label>
            <input type="file" class="form-control" name="document" value="{{old('document')}}">
            @error('document')
            <div class="alert alert-danger fs-6 text">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group mb-3" style="margin-top: 40px">
            <label class="fw-bold" style="font-size: 20px;">Video</label>
            <input type="file" class="form-control" name="video" value="{{old('video')}}">
            @error('video')
            <div class="alert alert-danger fs-6 text">{{ $message }}</div>
            @enderror
        </div>
        <button class="btn btn-dark mt-5" type="submit">Submit</button>
    </form>
</div>
@endsection