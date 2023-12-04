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
                    <div class="col-md-4 gradient-custom d-flex flex-column justify-content-center align-items-center text-center text-white"
                        style="border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem">
                        <img src="{{ url('img/profile_user.svg') }}" alt="Avatar" style="width: 150px" />
                        <h2 class="text-center fw-bold">{{ Auth::user()->username }}</h2>
                        <a href="/sign/{{Auth::user()->id}}" class="btn btn-primary">Sign PDF</a>
                        @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                        @endif
                        @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="card-body p-4">
                            <h2 class="fw-bold">AES</h2>
                            <hr class="mt-0 mb-4" />
                            <div class="row pt-1">
                                @foreach ($aess as $aes)
                                <div class="col-3 mb-3">
                                    <h6>Full Name</h6>
                                    <p class="text-muted">{{ $homeController->AESdecrypt($aes->fullname,
                                        $aes->fullname_key, $aes->fullname_iv, 0) }}</p>
                                </div>
                                <div class="col-3 mb-3">
                                    <h6>ID Card</h6>
                                    @php
                                    $akey = $aes->id_card_key;
                                    $akey = str_replace('/', '', $akey);
                                    $bkey = $aes->document_key;
                                    $bkey = str_replace('/', '', $bkey);
                                    $ckey = $aes->video_key;
                                    $ckey = str_replace('/', '', $ckey);
                                    @endphp
                                    <a href="/download/aes/id_card/{{$aes->user_id}}/{{$akey}}"
                                        class="btn btn-primary btn-sm">Download</a>
                                </div>
                                <div class="col-3 mb-3">
                                    <h6>Document</h6>
                                    <a href="/download/aes/document/{{$aes->user_id}}/{{$bkey}}"
                                        class="btn btn-primary btn-sm">Download</a>
                                </div>
                                <div class="col-3 mb-3">
                                    <h6>Video</h6>
                                    <a href="/download/aes/video/{{$aes->user_id}}/{{$ckey}}"
                                        class="btn btn-primary btn-sm">Download</a>
                                </div>
                                @endforeach
                            </div>
                            <h2 class="fw-bold">RC4</h2>
                            <hr class="mt-0 mb-4" />
                            <div class="row pt-1">
                                @foreach ($rc4s as $rc4)
                                <div class="col-3 mb-3">
                                    <h6>Full Name</h6>
                                    <p class="text-muted">{{ $homeController->Rc4decrypt($rc4->fullname, $rc4->key, 0)
                                        }}</p>
                                </div>
                                <div class="col-3 mb-3">
                                    <h6>ID Card</h6>
                                    @php
                                    $dkey = $rc4->key;
                                    $dkey = str_replace('/', '', $dkey);
                                    @endphp
                                    <a href="/download/rc4/id_card/{{$rc4->user_id}}/{{$dkey}}"
                                        class="btn btn-secondary btn-sm">Download</a>
                                </div>
                                <div class="col-3 mb-3">
                                    <h6>Document</h6>
                                    <a href="/download/rc4/document/{{$rc4->user_id}}/{{$dkey}}"
                                        class="btn btn-secondary btn-sm">Download</a>
                                </div>
                                <div class="col-3 mb-3">
                                    <h6>Video</h6>
                                    <a href="/download/rc4/video/{{$rc4->user_id}}/{{$dkey}}"
                                        class="btn btn-secondary btn-sm">Download</a>
                                </div>
                                @endforeach
                            </div>
                            <h2 class="fw-bold">DES</h2>
                            <hr class="mt-0 mb-4" />
                            <div class="row pt-1">
                                @foreach ($dess as $des)
                                <div class="col-3 mb-3">
                                    <h6>Full Name</h6>
                                    <p class="text-muted">{{ $homeController->Desdecrypt($des->fullname, $des->key,
                                        $des->iv, 0) }}</p>
                                </div>
                                <div class="col-3 mb-3">
                                    <h6>ID Card</h6>
                                    @php
                                    $ekey = $des->key;
                                    $ekey = str_replace('/', '', $ekey);
                                    @endphp
                                    <a href="/download/des/id_card/{{$des->user_id}}/{{$ekey}}"
                                        class="btn btn-success btn-sm">Download</a>
                                </div>
                                <div class="col-3 mb-3">
                                    <h6>Document</h6>
                                    <a href="/download/des/document/{{$des->user_id}}/{{$ekey}}"
                                        class="btn btn-success btn-sm">Download</a>
                                </div>
                                <div class="col-3 mb-3">
                                    <h6>Video</h6>
                                    <a href="/download/des/video/{{$des->user_id}}/{{$ekey}}"
                                        class="btn btn-success btn-sm">Download</a>
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
    <h1 class="text-center display-5 fw-bold" style="margin-top: 100px">Hi {{ Auth::user()->username }}, please complete
        your profile!</h1>
    @endif


</div>
@endsection