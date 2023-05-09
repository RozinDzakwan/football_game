@extends('eye_football.main')
@section('title')
    EYE Sport Football League
@endsection
@section('content')
    <div class="container pt-5">
        <div class="content row justify-content-center">
            <div class="col-10 text-center logo">
                <img src="{{ asset('asset/images/etc/logo_main.png') }}" class="logo-main">
            </div>
            <div class="card-body">
                <form action="{{ route('playgame') }}" method="POST">
                    @csrf
                    @livewire('registration-form', [
                        'data_cookie' => $data_cookie,
                        'all_data_teams' => $data_teams,
                        'privacy' => $privacy,
                    ])
                </form>
            </div>
        </div>
    </div>
@endsection
