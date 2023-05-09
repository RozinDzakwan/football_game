<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade @if ($template == 1) show active @endif pb-3" id="nav-profile" role="tabpanel"
        aria-labelledby="nav-profile-tab">
        <h1 class="fb-label-login">LOGIN</h1>
        <div class="row pt-3">
            <div class="col-12">
                <label class="fb-label-input">
                    E-MAIL * :
                </label>
                <input type="email" wire:change.debounce.750ms='checkEmail()' wire:model='email' class="fb-input"
                    name="email" required id="email" placeholder="Email">
                <input type="hidden" name="score" value="{{ $score }}">
                @if (!empty($emailError))
                    <div>
                        <span class="bg-danger"
                            style="font-family: 'Montserrat';font-weight: 500;font-size: 10pt;">{{ $emailError }}</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="row pt-3">
            <div class="col-6">
                <label class="fb-label-input">
                    FIRST NAME :
                </label><br>
                <input type="text" class="fb-input" name="name" value="{{ $name }}" wire:model='name'
                    placeholder="First Name">
            </div>
            <div class="col-6">
                <label class="fb-label-input">
                    LAST NAME :
                </label>
                <input type="text" class="fb-input" name="lastname" wire:model='lastname' value="{{ $lastname }}"
                    placeholder="Last Name">
            </div>
        </div>
        <div class="row pt-3">
            <div class="col-6">
                <label class="fb-label-input">
                    NICKNAME * :
                </label><br>
                <input type="text" wire:change.debounce.750ms='checkNickname()' wire:model='nickname'
                    class="fb-input" name="nickname" value="{{ $nickname }}" required id="nickname"
                    placeholder="Nickname">
                @if (!empty($nicknameError))
                    <span class="bg-danger"
                        style="font-family: 'Montserrat';font-weight: 500;font-size: 10pt;">{{ $nicknameError }}</span>
                @endif
            </div>
            <div class="col-6">
                <label class="fb-label-input">
                    AGE :
                </label>
                <input type="number" class="fb-input" name="age" value="{{ $age }}" placeholder="Age"
                    wire:model='age'>
            </div>
        </div>
        <div class="form-check pt-2" style="margin-left:50px">
            <input class="form-check-input" type="checkbox" wire:model="privacy" name="privacy" value="accept"
                id="accept" wire:click="checkPrivacy()" @if ($privacy == 'accept') checked @endif>
            <label class="form-check-label" for="accept"
                style="font-size: 9pt;font-family: Montserrat,sans-serif; font-weight: 800;">
                I read and accepted the <a href="https://www.iubenda.com/privacy-policy/89206311?ifr=true&height=800"
                    target="blank">
                    privacy policy</a>
            </label>
        </div>
        <div class="d-flex justify-content-center row py-3">
            <button class="btn col-5" id="button-next-default" wire:click="checkTemplate('2')" type="button"
                @if ($email == null || $nickname == null || $emailError != '' || $nicknameError != '' || $privacy != 'accept') disabled @endif>
                <img src="{{ asset('asset/images/etc/next_default.png') }}" class="image-start-game">
            </button>
            <button class="btn col-5" id="button-next-clicked" wire:click="checkTemplate('2')" type="button"
                @if ($email == null || $nickname == null || $emailError != '' || $nicknameError != '') disabled @endif>
                <img src="{{ asset('asset/images/etc/next_clicked.png') }}" class="image-start-game">
            </button>
            <div class="d-flex justify-content-center mt-2" style="font-size:.75rem; font-weight:300">
                * = mandatory
            </div>
        </div>
    </div>
    <div class="tab-pane fade @if ($template == 2) show active @endif" id="nav-player" role="tabpanel"
        aria-labelledby="nav-player-tab">
        <div class="row d-flex justify-content-center">
            <h1 class="fb-label-login">LOCKER ROOM</h1>
            <div class="col-lg-12 pb-3">
                <label class="pb-2 fb-label-input">Choose Team</label><br>
                <select name="player_team" class="fb-select" wire:model="player_team" wire:change="changeTeam()"
                    required>
                    @foreach ($all_data_teams as $name_team => $data_team)
                        <option @selected($name_team == $player_team) id="{{ $name_team }}">
                            {{ $name_team }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                @if ($custom_template == false)
                    <label class="pb-2 fb-label-input">Choose Player</label><br>
                    <div class="row">
                        <div class="col-12 pb-2">
                            <select name="player_name" class="fb-select" id="player-option" required
                                wire:model="player_name">
                                @foreach ($all_data_teams[$player_team]['player-team'] as $key => $value)
                                    <option @selected($key == $player_name) value="{{ $key }}">
                                        {{ str_replace('-', ' ', $value) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <button class="btn btn-primary" id="button-custom" type="button"
                                style="border-radius: 20px!important"
                                wire:click="changePlayerName('custom')">Custom</button>
                        </div>
                    </div>
                @else
                    <label class="pb-2 fb-label-input">Custom Player</label><br>
                    <div class="row">
                        <div class="col-12 pb-2">
                            <div class="row">
                                <div class="col-3">
                                    <input type="number" name="number_custom" class="fb-input" min="1"
                                        placeholder="No" value="{{ $number_custom }}" required
                                        wire:model="number_custom" max="99">
                                </div>
                                <div class="col-9">
                                    <input type="text" name="name_custom" class="fb-input" placeholder="Name"
                                        value="{{ $name_custom }}" required maxlength="9"
                                        wire:model="name_custom">
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <button class="btn btn-secondary" type="button" style="border-radius: 20px!important"
                                wire:click="changePlayerName('cancel')">Choose</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="text-center py-3">
            <h5 class="fb-label-input" style="font-size: 13pt!important">Choose Shirt</h5>
        </div>
        <div class="container-carousel">
            <?php $no = 1; ?>
            @foreach ($all_data_teams[$player_team]['shirt-team'] as $key => $value)
                @if ($player_shirt != null)
                    <input type="radio" id="item-{{ $no }}" @checked($key == $player_shirt)
                        name="player_shirt" value="{{ $key }}">
                @else
                    <input type="radio" id="item-{{ $no }}" @checked($no == 1)
                        name="player_shirt" value="{{ $key }}">
                @endif
                <?php $no++; ?>
            @endforeach
            <?php $no = 1; ?>
            <div class="cards">
                @foreach ($all_data_teams[$player_team]['shirt-team'] as $key => $value)
                    <label class="card" for="item-{{ $no }}" id="song-{{ $no }}">
                        <img src="{{ asset('asset/images/shirt/') }}/{{ $value }}" class="shirt-image">
                    </label>
                    <?php $no++; ?>
                @endforeach
            </div>
        </div>
        <div class="d-flex justify-content-center row py-2">
            <button class="btn col-5" id="button-back-default" wire:click="checkTemplate('1')" type="button">
                <img src="{{ asset('asset/images/etc/button_back_default.png') }}" class="image-start-game">
            </button>
            <button class="btn col-5" id="button-back-clicked" wire:click="checkTemplate('1')" type="button">
                <img src="{{ asset('asset/images/etc/button_back_clicked.png') }}" class="image-start-game">
            </button>
            <button class="btn col-5" id="button-start-default" wire:click="checkTemplate('2')" type="submit">
                <img src="{{ asset('asset/images/etc/button_start_default.png') }}" class="image-start-game">
            </button>
            <button class="btn col-5" id="button-start-clicked" wire:click="checkTemplate('2')" type="submit">
                <img src="{{ asset('asset/images/etc/button_start_clicked.png') }}" class="image-start-game">
            </button>
        </div>
        <div class="row justify-content-center">
            <a href="{{ route('resultplayer') }}" class="btn col-5" id="button-leaderboard-default"
                wire:click="checkTemplate('2')" type="button">
                <img src="{{ asset('asset/images/etc/leaderboard_default.png') }}" class="image-start-game">
            </a>
            <a class="btn col-5" id="button-leaderboard-clicked" wire:click="checkTemplate('2')" type="button">
                <img src="{{ asset('asset/images/etc/leaderboard_clicked.png') }}" class="image-start-game">
            </a>
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
