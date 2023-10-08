@extends('master')
@include('navbar')
@php
    $homeController = app('App\Http\Controllers\HomeController');
@endphp
@section('content')
<div class="container">
    @if(count($aess) > 0)
        <!-- <h1 class="text-center display-5 fw-bold" style="margin-top: 100px">Hi {{ Auth::user()->username }}, this is your profile!</h1> -->
        <div class="container py-5 h-100 vh-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="card mb-3" style="border-radius: 0.5rem">
                    <div class="row">
                        <div class="col-md-4 gradient-custom d-flex flex-column justify-content-center align-items-center text-center text-white" style="border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem">
                            <img src="{{ url('img/profile_user.svg') }}" alt="Avatar" style="width: 150px" />
                            <h2 class="text-center fw-bold">{{ Auth::user()->username }}</h2>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body p-4">
                                <h2 class="fw-bold">AES</h2>
                                <hr class="mt-0 mb-4" />
                                <div class="row pt-1">
                                    @foreach ($aess as $aes)
                                    <div class="col-3 mb-3">
                                        <h6>Full Name</h6>
                                        <p class="text-muted">{{ $homeController->AESdecrypt($aes->fullname, 0) }}</p>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <h6>ID Card</h6>
                                        <a href="/download/aes/id_card/{{$aes->id}}" class="btn btn-primary btn-sm">Download</a>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <h6>Document</h6>
                                        <a href="/download/aes/document/{{$aes->id}}" class="btn btn-primary btn-sm">Download</a>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <h6>Video</h6>
                                        <a href="/download/aes/video/{{$aes->id}}" class="btn btn-primary btn-sm">Download</a>
                                    </div>
                                    @endforeach
                                </div>
                                <h2 class="fw-bold">RC4</h2>
                                <hr class="mt-0 mb-4" />
                                <div class="row pt-1">
                                    @foreach ($rc4s as $rc4)
                                    <div class="col-3 mb-3">
                                        <h6>Full Name</h6>
                                        <p class="text-muted">{{ $homeController->Rc4decrypt($rc4->fullname, $rc4->key, 0) }}</p>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <h6>ID Card</h6>
                                        <a href="/download/rc4/id_card/{{$rc4->id}}" class="btn btn-secondary btn-sm">Download</a>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <h6>Document</h6>
                                        <a href="/download/rc4/document/{{$rc4->id}}" class="btn btn-secondary btn-sm">Download</a>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <h6>Video</h6>
                                        <a href="/download/rc4/video/{{$rc4->id}}" class="btn btn-secondary btn-sm">Download</a>
                                    </div>
                                    @endforeach
                                </div>
                                <h2 class="fw-bold">DES</h2>
                                <hr class="mt-0 mb-4" />
                                <div class="row pt-1">
                                    @foreach ($dess as $des)
                                    <div class="col-3 mb-3">
                                        <h6>Full Name</h6>
                                        <p class="text-muted">{{ $homeController->Desdecrypt($des->fullname, $des->key, $des->iv, 0) }}</p>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <h6>ID Card</h6>
                                        <a href="/download/des/id_card/{{$des->id}}" class="btn btn-success btn-sm">Download</a>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <h6>Document</h6>
                                        <a href="/download/des/document/{{$des->id}}" class="btn btn-success btn-sm">Download</a>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <h6>Video</h6>
                                        <a href="/download/des/video/{{$des->id}}" class="btn btn-success btn-sm">Download</a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <h1 class="text-center display-5 fw-bold" style="margin-top: 100px">Hi {{ Auth::user()->username }}, please complete your profile!</h1>
    @endif

    <!-- <h3>AES</h3>
    @foreach ($aess as $aes)
        Full Name: {{ $homeController->AESdecrypt($aes->fullname, 0) }} <br>
        ID-Card: <a href="/download/aes/id_card/{{$aes->id}}" class="btn btn-dark btn-sm">Download</a> <br>
        Document: <a href="/download/aes/document/{{$aes->id}}" class="btn btn-dark btn-sm">Download</a> <br>
        Video: <a href="/download/aes/video/{{$aes->id}}" class="btn btn-dark btn-sm">Download</a> <br>
    @endforeach

    <h3>Rc4</h3>
    @foreach ($rc4s as $rc4)
        Full Name: {{ $homeController->Rc4decrypt($rc4->fullname, $rc4->key, 0) }} <br>
        ID-Card: <a href="/download/rc4/id_card/{{$rc4->id}}" class="btn btn-dark btn-sm">Download</a> <br>
        Document: <a href="/download/rc4/document/{{$rc4->id}}" class="btn btn-dark btn-sm">Download</a> <br>
        Video: <a href="/download/rc4/video/{{$rc4->id}}" class="btn btn-dark btn-sm">Download</a> <br>
    @endforeach

    <h3>Des</h3>
    @foreach ($dess as $des)
        Full Name: {{ $homeController->Desdecrypt($des->fullname, $des->key, $des->iv, 0) }} <br>
        ID-Card: <a href="/download/des/id_card/{{$des->id}}" class="btn btn-dark btn-sm">Download</a> <br>
        Document: <a href="/download/des/document/{{$des->id}}" class="btn btn-dark btn-sm">Download</a> <br>
        Video: <a href="/download/des/video/{{$des->id}}" class="btn btn-dark btn-sm">Download</a> <br>
    @endforeach -->
</div>
@endsection