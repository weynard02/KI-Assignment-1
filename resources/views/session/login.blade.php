@extends('layout/aplication')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100" style="background-color: #d1e5e6">
    <div class="row mb-4 border rounded-5 p-3 bg-white shadow box-area">
        <div class="col-md-6 rounded-5 d-flex justify-content-center align-items-center flex-column left-box">
            <div class="featured-image">
                <img src="{{ url('img/login_page.svg') }}" alt="" class="img-fluid" />
            </div>
        </div>

        <div class="col-md-6 right-box my-auto">
            <div class="row align-items-center">
                <div>
                    <p class="text-center display-5 fw-bold">Login</p>
                </div>
            </div>
            <form action="/login" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-outline mb-4">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" placeholder="Username" name="username" />
                </div>

                <!-- Password input -->
                <div class="form-outline mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" placeholder="Password" name="password" />
                </div>
                <!-- Submit button -->
                <button type="submit" class="btn btn-dark align-items-center w-100 d-flex flex-column mb-4" style="background-color: #202020">LOGIN</button>
            </form>

            <div class="d-flex align-items-center justify-content-center pb-4">
                <p class="mb-0 me-2">Don't have an account?</p>
                <a href="/register" class="btn btn-outline-dark">Create new</a>
            </div>
        </div>
    </div>
</div>
@endsection