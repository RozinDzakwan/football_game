<?php

namespace App\Http\Livewire;

use App\Http\Helpers\ScCookies;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class RegistrationForm extends Component
{
    public $id_user;
    public $email;
    public $name;
    public $lastname;
    public $age;
    public $nickname;
    public $player_team;
    public $player_name;
    public $player_shirt;
    public $name_custom;
    public $number_custom;
    public $score;
    public string $emailError = '';
    public string $nicknameError = '';
    public mixed $data_cookie;
    public mixed $all_data_teams;
    public $template = 1;
    public $custom_template;
    public $privacy;

    public function mount()
    {
        if (!empty($this->data_cookie)) {
            foreach ($this->data_cookie as $key => $value) {
                if ($value == null || $value == false) {
                    if ($key == 'player_team') {
                        $this->$key = 'Cagliari Calcio';
                    } else {
                        $this->$key = '';
                    }
                } else {
                    $this->$key = $this->data_cookie[$key];
                }
            }
        }
        $this->checkTemplate(0);
        $this->checkPlayerName();
    }

    public function checkPlayerName()
    {
        if ($this->player_name == '') {
            $this->name_custom = '';
            $this->number_custom = '';
            $this->custom_template = false;
        } else if (!is_numeric($this->player_name)) {
            $this->custom_template = true;
            $player = (explode("-", $this->player_name));
            $this->number_custom = $player[0];
            $this->name_custom = $player[1];
        } else {
            $this->name_custom = '';
            $this->number_custom = '';
            $this->custom_template = false;
        }
    }

    public function checkPrivacy()
    {
        ScCookies::setFunctionalCookie('privacy', 'accept');
    }

    public function checkTemplate($number)
    {
        if ($number == 0) {
            if ($this->data_cookie['id'] == false) {
                $this->template = 1;
            } else {
                $this->template = 2;
            }
        } else {
            $data_template1 = [
                'email',
                'name',
                'lastname',
                'nickname',
                'age'
            ];
            foreach ($data_template1 as $value) {
                $data[$value] = $this->$value;
            }

            $user = User::where('email', $this->email)->first();
            if ($user == null) {
                User::create($data);
            } else {
                $user->update($data);
            }
            foreach ($data as $array => $value) {
                ScCookies::setFunctionalCookie($array, $value);
            }
            $this->template = $number;
        }
    }

    public function checkEmail()
    {
        $this->emailError = $this->validateEmail($this->email);
        $data_user = User::where('email', $this->email)->first();
        if (!empty($data_user)) {
            $this->id_user = $data_user->id;
            $this->name = empty($this->name) ? $data_user->name : $this->name;
            $this->lastname = empty($this->lastname) ? $data_user->lastname : $this->lastname;
            $this->age = empty($this->age) ? $data_user->age : $this->age;
            $this->nickname = $data_user->nickname;
            $this->player_team = $data_user->player_team;
            $this->player_name = $data_user->player_name;
            $this->player_shirt = $data_user->player_shirt;
            $this->score = $data_user->score;
            $this->nicknameError = '';
        } else {
            $this->id_user = 0;
            $this->score = 0;
            $this->player_team = 'Cagliari Calcio';
            $this->player_name = null;
            $this->player_shirt = null;
            $this->name_custom = null;
            $this->number_custom = null;
            if($this->nickname != null){
                $check = User::where('nickname', $this->nickname)->first();
                if($check != null){
                    $this->nicknameError = 'Nickname has been used';
                }
            }
        }
        $this->checkPlayerName();
    }

    public function checkNickname()
    {
        $data['nickname'] = $this->nickname;
        $validation = Validator::make($data, [
            'nickname' => 'required|unique:users,nickname,' . $this->id_user
        ]);
        if ($validation->fails()) {
            $this->nicknameError = 'Nickname has been used';
        } else {
            $this->nicknameError = '';
        }
        if ($this->nickname == '') {
            $this->nicknameError = 'Nickname cannot be empty!';
        }
    }

    private function validateEmail($email)
    {
        if (empty($email)) {
            return 'The email cannot be empty!';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }
        return '';
    }

    public function changePlayerName($action)
    {
        if ($action == 'cancel') {
            $this->name_custom = '';
            $this->number_custom = '';
            $this->custom_template = false;
        } else {
            $this->custom_template = true;
        }
        // dd($this->custom_template);
        $this->checkTemplate(2);
    }

    public function changeTeam()
    {
        $this->player_shirt = null;
    }

    public function render()
    {
        return view('livewire.registration-form');
    }
}
