<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $akun = auth()->user();
        $data = '';

        if( $akun->role_id == 1) {
            $data = $akun->admin;
        }

        if( $akun->role_id == 2) {
            $data = $akun->mahasiswa;
        }

        if( $akun->role_id == 3) {
            $data = $akun->dosen;
        }

        return view('dashboard/dashboard', ['data' => $data]);
    }
}
