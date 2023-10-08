<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Aes;
use App\Models\Des;
use App\Models\Rc4;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function welcome(){
        $aess = Aes::where('user_id', Auth::id())->get();
        return view('session.welcome', compact('aess'));
    }

    public function index(){
        return view('session.login');
    }

    public function login(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ], 
        [
            'username.required' => 'Username can\'t be empty!',
            'password.required' => 'Password can\'t be empty!'
        ]);

        $data = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($data)) return redirect('/home');
        else return view('session.login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }


    public function register(){
        return view('session.register');
    }

    public function create(Request $request){
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6'
        ], 
        [
            'username.required' => 'Username can\'t be empty!',
            'username.unique' => 'Username is already taken!',
            'password.required' => 'Password can\'t be empty!',
            'password.min' => 'Minimum password length is 6 characters!'
        ]);

        $data = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        User::create($data);

        if (Auth::attempt($data)) return redirect('/home');
        else return view('session.login');
    }
}
