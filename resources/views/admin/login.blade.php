@extends('admin.layout')
@section('title')
    Admin
@endsection
@section('content')
    <div class="container pt-5">
        <div class="content row justify-content-center">
            <div class="col-10 text-center logo">
                <img src="{{ asset('asset/images/etc/logo_main.png') }}" class="logo-main">
            </div>
            <div class="card-body" style="margin-top:60px">
                <form action="{{ route('loginAdmin') }}" method="post">
                    @csrf
                    <label class="fb-label-input">Input Password</label>
                    <input type="password" name="password" class="fb-input" required>
                    <div class="text-end">
                        <button class="btn btn-success mt-4" style="margin-right:10px">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
