@extends('master')
@include('navbar')
@php
$homeController = app('App\Http\Controllers\HomeController');
@endphp
@section('content')
<div class="container">
    <div class="text-center mb-5" style="margin-top: 100px">
        <!-- Center the content -->
        <div class="col">
            <h1 class="text-center display-5 fw-bold" style="margin-top: 100px">{{$user->username}}'s document</h1>
            <br />
        </div>
    </div>

    <div id="mycard" class="d-flex justify-content-center">
        <div class="card mb-3 col-lg-6 mx-3">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <div class="form-group mb-3 p-2">
                        <label class="fw-bold mb-3" style="font-size: 20px;">Decrypt key from your email</label>
                        <textarea id="encsymkey" rows="5" class="form-control" name="encsymkey" placeholder="Enter the key from your email" value=""></textarea>
                    </div>

                    <div class="d-flex justify-content-end mb-5">
                        <button id="submitButton1" class="btn btn-dark mx-2" type="submit">Submit</button>
                    </div>

                    <div class="mb-3 p-2">
                        <label class="fw-bold mb-3" style="font-size: 20px;">Here is your symmetric key</label>
                        <textarea id="outputTextarea" class="form-control" rows="2" readonly></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3 col-lg-5 mx-3">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <div class="form-group mb-3 p-2">
                        <label class="fw-bold mb-3" style="font-size: 20px;">Symmetric Key</label>
                        @if($inbox !== null)
                        <input type="hidden" class="form-control" id="realsymkey" value="{{$inbox->sym_key}}">
                        @endif
                        <textarea id="symkey" rows="5" class="form-control" name="symkey" placeholder="Enter the symmetric key" value=""></textarea>
                    </div>

                    <div class="d-flex justify-content-end mb-5">
                        <button id="submitButton2" class="btn btn-dark mx-2" type="submit">Submit</button>
                    </div>
                    {{-- {{ route('mail.document', ['key' => $user->id]) }} --}}
                    <form action="/home/inbox/document/{{(int)$aesuser->user_id}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-5 p-2 d-flex justify-content-between align-items-center">
                            <label class="fw-bold mb-3" style="font-size: 20px;">Not requested yet?</label>
                            <button class="btn btn-dark" type="submit">Request</button>
                        </div>
                    </form>

                    <div class="form-group mb-2 p-2 d-block visually-hidden" id="hiddendata">
                        <label class="fw-bold mb-2" style="font-size: 20px;">Here is {{$user->username}}'s
                            document</label>
                        @php
                        $bkey = null;

                        if ($inbox !== null) {
                        $bkey = str_replace('/', '', $inbox->sym_key);
                        }
                        @endphp

                        @if($bkey !== null)
                        <a href="/download/aes/document/{{ $aesuser->user_id }}/{{ $bkey }}" class="btn btn-primary btn-sm">
                            Download
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="mycard" class="d-flex justify-content-center">
        <div class="card mt-3 mb-5 col-lg-11 mx-3">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <form action="/verify/{{(int)$aesuser->user_id}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3 p-2">
                            <label class="fw-bold mb-3" style="font-size: 20px;">Verify Document</label>
                            <input type="file" class="form-control" name="document" required>
                            @error('document')
                            <div class="alert alert-danger fs-6 text">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button id="verifybutton" class="btn btn-dark mt-1" type="submit">Submit</button>
                        </div>
                    </form>

                    @if(session('status') == 'success')
                        <div class="d-flex mt-5">
                            <div class="form-group col-6 mb-3 p-2">
                                <label class="fw-bold mb-3" style="font-size: 20px;">Digest from Document</label>
                                <textarea class="form-control" rows="2" readonly>{{session('digest')}}</textarea>
                            </div>
                            <div class="form-group col-6 mb-3 p-2">
                                <label class="fw-bold mb-3" style="font-size: 20px;">Decrypted Digital Signature</label>
                                <textarea class="form-control" rows="2" readonly>{{session('decrypted_digsig')}}</textarea>
                            </div>
                        </div>
                        @if(session('digest') == session('decrypted_digsig'))
                        <div class="alert alert-success" role="alert">Digest and decrypted digital signature are the same! The document is verified.</div>
                        @else
                        <div class="alert alert-danger" role="alert">Digest and decrypted digital signature are not the same! Something in the document has changed.</div>
                        @endif
                    @elseif(session('status') == 'failed')
                    <div class="alert alert-danger" role="alert">{{session('message')}}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('submitButton1').addEventListener('click', function() {
        var inputValue = document.getElementById('encsymkey').value;

        if (inputValue.trim() !== '') {
            $.ajax({
                url: '/home/data/document/{{$user->id}}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    encsymkey: inputValue
                },
                success: function(response) {
                    console.log(response);
                    document.getElementById('outputTextarea').value = response.decrypted;
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    });

    document.getElementById('submitButton2').addEventListener('click', function() {
        var inputValue = document.getElementById('symkey').value;
        var realsymkey = document.getElementById('realsymkey').value;
        var hiddenDataDiv = document.getElementById('hiddendata');

        if (inputValue == realsymkey) {
            if (hiddenDataDiv.classList.contains('visually-hidden')) {
                hiddenDataDiv.classList.remove('visually-hidden');
            }
        } else {
            if (!hiddenDataDiv.classList.contains('visually-hidden')) {
                hiddenDataDiv.classList.add('visually-hidden');
            }
        }
    });
</script>
@endsection