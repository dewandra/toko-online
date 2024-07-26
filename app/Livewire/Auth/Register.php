<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('register - TokOl')]
class Register extends Component
{

    public $name;
    public $email;
    public $password;
    public function save()
    {
        $this->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6|max:255',
        ]);

        // SAVE TO DB
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' =>Hash::make($this->password),
        ]);

        // LOGIN USER
        auth()->login($user);

        // REDIRECT TO HOMEPAGE
        return redirect()->intended();
    }
    public function render()
    {
        return view('livewire.auth.register');
    }
}
