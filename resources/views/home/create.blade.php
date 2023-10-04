@extends('layout/aplication')

@section('content')
<nav class="navbar navbar-expand-lg navbar-dark bg-black fixed-top">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto fw-semibold">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/home/create">Upload</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/home">Profile</a>
                </li>
                <li><a class="nav-link" href="/logout">Log Out</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h1 class="text-center display-5 fw-bold" style="margin-top: 100px">Complete Your Profile</h1>
    <form action="/home" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group mb-3" style="margin-top: 40px">
            <label class="fw-bold" style="font-size: 20px;">Full Name</label>
            <input type="text" class="form-control" name="fullname" placeholder="Enter your Full Name">
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
        <button class="btn btn-dark mt-5" type="submit">Submit</button>
    </form>
</div>
@endsection