@extends('master')
@include('navbar')
@section('content')
<div class="container">
    <div class="text-center mb-5" style="margin-top: 100px">
        <!-- Center the content -->
        <div class="col">
            <h1 class="display-4 fw-bolder mb-4">List User</h1>
            <!-- <input class="form-control" id="myInput" type="text" placeholder="Search.." /> -->
            <form class="form-inline">
                <input class="form-control mr-sm-2" id="filter" type="search" placeholder="Search" aria-label="Search"
                    autocomplete="off" />
            </form>
            <br />
        </div>
    </div>
    <div id="mycard">
        @foreach($usernames as $username)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex flex-column flex-lg-row">
                    <span class="avatar avatar-text rounded-3 me-4 mb-2"><img src="{{ url('img/profile_user.svg') }}"
                            alt="Avatar" style="width: 80px" /></span>
                    <div class="row flex-fill">
                        <div class="col-sm-6 m-auto">
                            <h4 class="h5">{{$username->username}}</h4>
                        </div>
                        <div class="col-sm-6 text-lg-end m-auto">
                            <a href="/home/data/fullname/{{$username->id}}" class="btn btn-primary stretched-link">See Fullname</a>
                            <a href="/home/data/id_card/{{$username->id}}" class="btn btn-primary stretched-link">See ID Card</a>
                            <a href="/home/data/document/{{$username->id}}" class="btn btn-primary stretched-link">See Document</a>
                            <a href="/home/data/video/{{$username->id}}" class="btn btn-primary stretched-link">See video</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <script>
        $(document).ready(function () {
            $("#filter").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#mycard > div").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</div>
@endsection