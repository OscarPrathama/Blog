@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container my-5">
    <div class="row">

        {{-- image --}}
        <div class="col-md-3 col-sm-12 img-thumbnail-wrapper">
            <img src="http://localhost:8000/storage/upload/2021/03/pain.png"
                 class="img-thumbnail d-block" alt="">
            <a href="#" class="d-lg-block text-decoration-none">Remove</a>
            <div class="my-2">
                <input class="form-control" type="file" id="user_img" name="user_img">
            </div>
        </div>

        {{-- form --}}
        <div class="col-md-5 col-sm-12 profile-form-wrapper">
            <form action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ $user->name }}">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" value="{{ $user->email }}">
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">Set New Password</label>
                    <input type="text" class="form-control" id="new_password" name="new_password">
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary mb-3">Confirm identity</button>
                </div>
            </form>
        </div>

    </div>
</div>
@stop
