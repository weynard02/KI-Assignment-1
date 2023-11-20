<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Aes;
use App\Models\Des;
use App\Models\Rc4;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpseclib3\Crypt\RSA;

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
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6'
        ], 
        [
            'username.required' => 'Username can\'t be empty!',
            'username.unique' => 'Username is already taken!',
            'email.required' => 'Email can\'t be empty!',
            'email.unique' => 'Email is already taken!',
            'password.required' => 'Password can\'t be empty!',
            'password.min' => 'Minimum password length is 6 characters!'
        ]);

        $private = RSA::createKey();
        $public = $private->getPublicKey();

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'public_key' => $public,
            'private_key' => $private
        ];

        User::create($data);

        if (Auth::attempt($data)) return redirect('/home');
        else return view('session.login');
    }
}
