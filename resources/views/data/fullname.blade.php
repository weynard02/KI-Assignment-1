@extends('master')
@include('navbar')
@section('content')
<div class="container">
    <div class="text-center mb-5" style="margin-top: 100px">
        <!-- Center the content -->
        <div class="col">
            <h1 class="text-center display-5 fw-bold" style="margin-top: 100px">{{$user->username}}'s fullname</h1>
            <br />
        </div>
    </div>

    <div id="mycard" class="d-flex justify-content-center">
        <div class="card mb-3 col-lg-6 mx-3">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <div class="form-group mb-3 p-2">
                        <div class="form-group mb-3 p-2">
                            <label class="fw-bold mb-3" style="font-size: 20px;">Decrypt key from your email</label>
                            <textarea id="encsymkey" rows="5" class="form-control" name="encsymkey" placeholder="Enter the key from your email" value=""></textarea>
                        </div>

                        <div class="d-flex justify-content-end mb-5">
                            <button id="submitButton" class="btn btn-dark mx-2" type="submit">Submit</button>
                        </div>

                        <div class="mb-3 p-2">
                            <label class="fw-bold mb-3" style="font-size: 20px;">Here is your symmetric key</label>
                            <textarea id="outputTextarea" class="form-control" rows="2" readonly></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3 col-lg-5 mx-3">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <form action="/home" method="post" enctype="multipart/form-data">
                        <div class="form-group mb-3 p-2">
                            <label class="fw-bold mb-3" style="font-size: 20px;">Symmetric key</label>
                            <input type="text" class="form-control" name="symkey" placeholder="Enter the symmetric key" value="">
                            @error('symkey')
                            <div class="alert alert-danger fs-6 text">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end mb-5">
                            <button class="btn btn-dark mx-2" type="submit">Submit</button>
                        </div>
                    </form>
                    <form action="/home" method="post" enctype="multipart/form-data">
                        <div class="form-group mb-5 p-2 d-flex justify-content-between align-items-center">
                            <label class="fw-bold mb-3" style="font-size: 20px;">Not requested yet?</label>
                            <input type="hidden" class="form-control" name="symkey" placeholder="Enter the symmetric key" value="">
                            <button class="btn btn-dark" type="submit">Request</button>
                        </div>
                    </form>
                    <div class="form-group mb-3 p-2 d-flex justify-content-between align-items-center">
                        <label class="fw-bold mb-3" style="font-size: 20px;">Here is {{$user->username}}'s fullname</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('submitButton').addEventListener('click', function() {
        var inputValue = document.getElementById('encsymkey').value;

        if (inputValue.trim() !== '') {
            $.ajax({
                url: '/home/data/fullname/{{$user->id}}',
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
</script>
@endsection