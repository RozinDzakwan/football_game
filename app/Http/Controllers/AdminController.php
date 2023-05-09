<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $email = $request->email;
        $nickname = $request->nickname;
        if(session()->get('login') != 'true'){
            flash()->addFlash('error', 'Cannot access !!');
            return redirect('/admin');
        }
        if($email != null && $nickname != null){
            $data_users = User::where('nickname', 'like', '%' . $nickname . '%')
            ->where('email', 'like', '%' . $email . '%')
            ->paginate(5);
        }else if($email == null && $nickname != null){
            $data_users = User::where('nickname', 'like', '%' . $nickname . '%')
            ->paginate(5);
        }else if($email != null && $nickname == null){
            $data_users = User::where('email', 'like', '%' . $email . '%')
            ->paginate(5);
        }else{
            $data_users = User::paginate(20);
        }
        $data_teams = config('football-game');
        return view('admin.view', compact('data_users', 'data_teams', 'nickname', 'email'));
    }

    public function printPDF()
    {
        $fileName = 'players.csv';
        $data_users = User::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('No', 'Email', 'Firstname', 'Lastname', 'Age', 'Nickname', 'Player Team', 'Player Number', 'Player Name', 'Date Created');
        $callback = function () use ($data_users, $columns) {
            $data_team = config('football-game');
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $no = 1;
            foreach ($data_users as $data_user) {
                $row['No'] = $no++;
                $row['Email']  = $data_user->email;
                $row['Firstname']    = $data_user->name;
                $row['Lastname']    = $data_user->lastname;
                $row['Age']  = $data_user->age;
                $row['Nickname']  = $data_user->nickname;
                $row['Player Team']  = $data_user->player_team;
                $data_player_team = $data_team[$data_user->player_team];

                //check custom player
                if (is_numeric($data_user->player_name)) {
                    $data_shirt = $data_player_team['player-team'][$data_user->player_name];
                } else {
                    $data_shirt = $data_user->player_name;
                }
                $data_player = explode("-", $data_shirt);
                $row['Player Number']  = $data_player[0];
                $row['Player Name']  = $data_player[1];
                //format date player
                $date = date("d, M Y H:i", strtotime($data_user->created_at));
                $row['Date Created'] = $date;

                fputcsv($file, array($row['No'], $row['Email'], $row['Firstname'], $row['Lastname'], $row['Age'], $row['Nickname'], $row['Player Team'], $row['Player Number'], $row['Player Name'], $row['Date Created']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show($id)
    {
        $data_user = User::find($id);
        return response()->json($data_user);
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|unique:users,email,' . $request->id,
            'nickname' => 'required|unique:users,nickname,' . $request->id
        ]);
        if($validation->fails()){
            flash()->addFlash('error', 'Validation fails, check data again !!');
            return redirect('/admin/index');
        }
        User::find($request->id)->update([
            'email' => $request->email,
            'nickname' => $request->nickname,
            'age' => $request->age,
            'player_team' => $request->player_team,
        ]);
        flash()->addFlash('success', 'Data updated successfully !!');
        return redirect('/admin/index');
    }

    public function delete($id)
    {
        User::find($id)->delete();
        flash()->addFlash('warning', 'Data has been deleted !!');
        return redirect('/admin/index');
    }

}

