<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $akun = auth()->user();
        $data = '';

        if( $akun->role == "Admin") {
            $data = $akun->admin;
        }

        if( $akun->role == "Mahasiswa") {
            $data = $akun->mahasiswa;
        }

        if( $akun->role == "Dosen") {
            $data = $akun->dosen;
        }

        return view('dashboard/dashboard', ['data' => $data]);
    }
}
