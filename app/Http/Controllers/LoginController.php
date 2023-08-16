<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    
    public function index() {
        return view('auth/login');
    }

    public function authenticate(Request $request) {

        $request -> validate([
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => __("Username cannot be empty"),
            'password.required' => __("Password cannot be empty")
        ]);

        $credential = [
            'username' => $request -> username,
            'password' => $request -> password
        ];

        if(Auth::attempt($credential)) {
            $request -> session() -> regenerate();
            return redirect()->intended('dashboard');
        }

        return back() -> with([
            "status" => __("The username or password you entered is incorrect")
        ]);

    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
