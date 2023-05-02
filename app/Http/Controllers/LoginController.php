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
            'username.required' => 'Username tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong'
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
            "status" => "Username atau password yang anda masukan salah"
        ]);

    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
