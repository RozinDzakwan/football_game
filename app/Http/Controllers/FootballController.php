<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ScCookies;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class FootballController extends Controller
{

    public function index()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        session()->put('template', 1);
        $data_teams = config('football-game');
        if (!empty(User::getByEmail(ScCookies::getFunctionalCookie('email'))) && ScCookies::getFunctionalCookie('email') != false) {
            $data_cookie = User::getByEmail(ScCookies::getFunctionalCookie('email'))->toArray();
        } else {
            $data_cookies = [
                'id',
                'email',
                'name',
                'lastname',
                'age',
                'nickname',
                'score',
                'player_team',
                'player_name',
                'player_shirt',
            ];
            foreach ($data_cookies as $value) {
                $data_cookie[$value] = ScCookies::getFunctionalCookie($value);
            }
        }
        $privacy = ScCookies::getFunctionalCookie('privacy');
        return view('eye_football.login', compact('data_teams', 'data_cookie', 'privacy'));
    }

    public function playGame(Request $request)
    {
        dd($request->all());
        //parse name and number player
        $data_teams = config('football-game');
        if ($request->number_custom != null && $request->name_custom != null) {
            $data_player = $request->number_custom . '-' . $request->name_custom;
            $player_name_number = $data_player;
        } else if ($request->action == 'playagain') {
            // dd($request->all());
            if (is_numeric($request->player_name)) {
                $data_player = $data_teams[$request->player_team]['player-team'][$request->player_name];
            } else {
                $data_player = $request->player_name;
            }
            $player_name_number = $request->player_name;
        } else {
            $player_name_number = $request->player_name;
            $data_player = $data_teams[$request->player_team]['player-team'][$request->player_name];
        }

        //data encode
        $player = (explode("-", $data_player));
        $data = [
            'email' => $request->email,
            'player_number' => $player[0],
            'player_name' => $player[1],
            'player_shirt' => $request->player_shirt,
        ];
        $data = json_encode($data);
        //store database
        if (!isset($request->action)) {
            if (!empty(User::getByEmail($request->email))) {
                User::getByEmail($request->email)->update([
                    'email' => $request->email,
                    'name' => $request->name,
                    'lastname' => $request->lastname,
                    'age' => $request->age,
                    'nickname' => $request->nickname,
                    'player_team' => $request->player_team,
                    'player_name' =>  $player_name_number,
                    'player_shirt' => $request->player_shirt,
                ]);
            } else {
                User::create([
                    'email' => $request->email,
                    'name' => $request->name,
                    'lastname' => $request->lastname,
                    'age' => $request->age,
                    'nickname' => $request->nickname,
                    'player_team' => $request->player_team,
                    'player_name' =>  $player_name_number,
                    'player_shirt' => $request->player_shirt,
                ]);
            }
        }
        //store cookie
        foreach ($request->except('_token') as $array => $value) {
            ScCookies::setFunctionalCookie($array, $value);
        }
        session()->put('check_authorized', 'accept');
        return view('game.game', compact('data'));
    }

    public function playGameFails()
    {
        return redirect('/');
    }

    public function storeScore($score)
    {
        $authorized = session()->get('check_authorized');
        if ($authorized != 'accept') {
            return redirect('/');
        }
        $email = ScCookies::getFunctionalCookie('email');
        $data = User::where('email', $email)->first();
        $final_score = $score + $data->score;
        $data->update([
            'score' => $final_score
        ]);
        session()->put('email', $email);
        session()->put('score', $score);
        session()->put('check_authorized', 'decline');
        $unique = bin2hex(random_bytes(5));
        return redirect()->route('result', $unique);
    }

    public function resultPlayer()
    {
        $email = ScCookies::getFunctionalCookie('email');
        session()->put('email', $email);
        session()->put('score', '0');
        $unique = bin2hex(random_bytes(5));
        return redirect()->route('result', $unique);
    }

    public function getResult($unique)
    {
        $email = session()->get('email');
        $score = session()->get('score');
        // dd($email, $score);
        if ($email == null || $score == null) {
            return redirect('/');
        }
        $data_user = User::where('email', $email)->first();
        $nickname = $data_user->nickname;

        $query =  DB::select("SELECT count(nickname) as position
        FROM `users` u
        WHERE nickname != '$nickname'
            AND score > (
                SELECT score
                FROM users
                WHERE nickname = '$nickname'
                ORDER BY score DESC, updated_at DESC
                LIMIT 1
            )");

        $position = $query[0]->position;
        if ($position <= 4) {
            $offset = 0;
        } else {
            $offset = $position - 4;
        }

        $data_leaderboards = DB::select("SELECT *
                            FROM users
                            ORDER BY score DESC, updated_at DESC
                            LIMIT $offset,9");
        if ($offset == 0) {
            $offset = 1;
        } else {
            $offset++;
        }
        $cookie_coupon = ScCookies::getFunctionalCookie('coupon');
        $final_score = session()->get('final_score');
        if ($score >= 50 && $cookie_coupon == null) {
            $check_coupon = true;
        } else {
            $check_coupon = false;
        }
        return view('eye_football.result', compact('check_coupon', 'data_leaderboards', 'offset', 'score', 'data_user', 'final_score'));
    }

    public function reedemCode()
    {
        ScCookies::setFunctionalCookie('coupon', true);
        return Redirect::to('https://www.eyesportshop.com/');
    }
}
