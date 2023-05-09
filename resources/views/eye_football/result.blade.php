@extends('eye_football.main')
@section('title')
    EYE Sport Football League
@endsection
@section('content')
    <style>
        #share-clicked {
            display: none;
        }

        #share-default:hover {
            cursor: pointer;
        }

        .fb-input-value {
            margin-left: 32px !important;
            margin-top: 9px !important;
            width: 50% !important;
            height: 50% !important;
        }
    </style>
    <div class="container-result pt-5">
        <div class="content row justify-content-center">
            <div class="col-10 text-center logo">
                <img src="{{ asset('asset/images/etc/logo_main.png') }}" class="logo-main">
            </div>
            <div class="card-body">
                <div style="text-align: center; margin-bottom: -7px; margin-top:45px">
                    <img src="{{ asset('asset/images/etc/leaderboard.png') }}" width="250px">
                </div>
                <div class="fb-leaderboard">
                    <div class="fb-table-thead row">
                        <div class="col-3" style="padding-right: 0px; padding-left:0px">
                            <label class="fb-leaderboard-thead">RANK</label>
                        </div>
                        <div class="col-6" style="padding-right: 0px; padding-left:0px">
                            <label class="fb-leaderboard-thead">NICKNAME</label>
                        </div>
                        <div class="col-3" style="padding-right: 0px; padding-left:0px">
                            <label class="fb-leaderboard-thead">SCORE</label>
                        </div>
                    </div>
                    <div class="fb-table-body" style="padding: 20px">
                        <?php $max = $offset + 8; ?>
                        @foreach ($data_leaderboards as $data_leaderboard)
                            <div class="fb-table-tbody text-center @if ($data_user->nickname == $data_leaderboard->nickname) active @endif row"
                                style="@if ($offset == $max) margin-bottom:0px @endif">
                                <div class="col-3">{{ $offset++ }}</div>
                                <div class="col-6">{{ $data_leaderboard->nickname }}</div>
                                <div class="col-3" style="padding-right: 0px; padding-left:0px">
                                    @if ($data_leaderboard->score > 99999)
                                        {{ number_format($data_leaderboard->score / 1000, 0) }}K
                                    @else
                                        {{ $data_leaderboard->score }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @if ($check_coupon == true)
                    <div class="d-flex justify-content-center pt-3">
                        <button type="button" class="btn btn-primary col-5" id="button-coupon" data-bs-toggle="modal"
                            data-bs-target="#couponModal">
                            Coupon 20%
                        </button>
                    </div>
                @endif
                <div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <a class="fb-close" data-bs-dismiss="modal">
                                    <img src="{{ asset('asset/images/etc/button_close.png') }}" class="fb-image-close">
                                </a>
                            </div>
                            <div class="modal-body">
                                <div class="text-center" style="margin-top: -9px">
                                    <label class="fb-label-modal">CONGRATULATIONS!</label>
                                </div>
                                <div class="coupon-row">
                                    <div class="coupon-content-big">
                                        <label>20% OFF</label>
                                    </div>
                                    <div class="coupon-content-small">
                                        <img src="{{ asset('asset/images/etc/logo_eye.jpeg') }}"
                                            class="image-start-game fb-image-eye">
                                    </div>
                                </div>
                                <div class="row pt-3">
                                    <div class="col-6">
                                        <a onclick="reedemCopy()">
                                            <img src="{{ asset('asset/images/etc/Form Box.png') }}" class="image-coupon">
                                            <label class="fb-label-coupon">LEAGUE20</label>
                                        </a>
                                        <input class="fb-input-value" type="text" id="coupon-code" value="LEAGUE20" readonly>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('reedemcode') }}" onclick="reedemCopy()">
                                            <img src="{{ asset('asset/images/etc/shopnow_default.png') }}"
                                                class="image-reedem" id="image-reedem-default">
                                        </a>
                                        <img src="{{ asset('asset/images/etc/shopnow_clicked.png') }}" class="image-reedem"
                                            id="image-reedem-clicked">
                                    </div>
                                    <label style="font-size: 6pt;padding-left: 16px">
                                        Click and copy to clipboard
                                    </label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div id="fb-loading-modal">
                                    <label class="fb-label-input">
                                        Loading...
                                    </label>
                                    <div class="lds-ring">
                                        <div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 mb-2">
                    <input type="hidden" id="refreshed" value="no">
                    <form action="{{ route('playgame') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $data_user->email }}">
                        <input type="hidden" name="player_name" value="{{ $data_user->player_name }}">
                        <input type="hidden" name="player_shirt" value="{{ $data_user->player_shirt }}">
                        <input type="hidden" name="player_team" value="{{ $data_user->player_team }}">
                        <input type="hidden" name="action" value="playagain">
                        <div class="row d-flex justify-content-center ">
                            <button type="submit" class="btn col-5" id="play-default">
                                <img src="{{ asset('asset/images/etc/play_default.png') }}" class="image-start-game"
                                    style="margin-top:-6px">
                            </button>
                            <button class="btn col-5" id="play-clicked">
                                <img src="{{ asset('asset/images/etc/play_clicked.png') }}" class="image-start-game"
                                    style="margin-top:-6px">
                            </button>
                            <a href="https://www.eyesportshop.com" class="col-5" id="store-default">
                                <img src="{{ asset('asset/images/etc/store_default.png') }}" class="image-start-game">
                            </a>
                            <a class="col-5" id="store-clicked">
                                <img src="{{ asset('asset/images/etc/store_clicked.png') }}" class="image-start-game">
                            </a>
                        </div>
                    </form>
                </div>
                <div class="row d-flex justify-content-center mt-3">
                    <div class="col-5">
                        <a href="{{ route('login') }}" id="choose-default">
                            <img src="{{ asset('asset/images/etc/choose_default.png') }}" class="image-start-game">
                        </a>
                        <a id="choose-clicked">
                            <img src="{{ asset('asset/images/etc/choose_clicked.png') }}" class="image-start-game">
                        </a>
                    </div>
                    <div class="col-5">
                        <a id="share-default">
                            <img src="{{ asset('asset/images/etc/share_default.png') }}" class="image-start-game">
                        </a>
                        <a id="share-clicked">
                            <img src="{{ asset('asset/images/etc/share_clicked.png') }}" class="image-start-game">
                        </a>
                    </div>
                </div>
            </div>
            <div id="fb-loading">
                <label class="fb-label-input">
                    Loading...
                </label>
                <div class="lds-ring">
                    <div></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const btn = document.getElementById("share-default");

        // function for web share api
        function webShareAPI(header, description, link) {
            navigator
                .share({
                    title: 'EYE Football League',
                    text: 'Keep Your Eye on Goal',
                    url: 'https://efl.eyesportwear.com'
                })
                .then(() => console.log("Successful share"))
                .catch((error) => console.log("Error sharing", error));
        }

        if (navigator.share) {
            btn.style.display = "block";
            btn.addEventListener("click", () =>
                webShareAPI("header", "description", "www.url.com")
            );
        }
    </script>
    <script>
        function reedemCopy() {
            var copyText = document.getElementById("coupon-code");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
        }
    </script>
@endsection
