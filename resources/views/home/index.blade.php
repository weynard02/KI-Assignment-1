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
                    <a class="nav-link" aria-current="page" href="/home/create">Upload</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/home">Profile</a>
                </li>
                <li><a class="nav-link" href="/logout">Log Out</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h1 class="text-center display-5 fw-bold" style="margin-top: 100px">Hi {{Auth::user()->username}}, this is your profile!</h1>
</div>
@endsection