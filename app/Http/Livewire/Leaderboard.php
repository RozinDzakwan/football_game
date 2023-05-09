<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Leaderboard extends Component
{
    public $data_leaderboards;
    public $offset;
    public $nickname;
    public function boot()
    {
        // dd($this->data_users);

    }

    public function render()
    {
        return view('livewire.leaderboard');
    }
}
