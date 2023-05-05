<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Presensi;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class KelasController extends Controller
{
    public function index()
    {
        Gate::allows('isDosen') ? Response::allow() : abort(403);
        return view('kelas/kelas', ['jadwal' => Jadwal::where('nip', auth()->user()->dosen->nip)->get()]);
    }

    public function show($id)
    {
        return view('kelas/detail_kelas', ['detail' => Jadwal::find($id)]);
    }

    public function presence(Request $request)
    {
        $waktuPresensi = date("H:i:s");
        $presensi = new Presensi([
            'jadwal_id' => $request->jadwal_id,
            'nim' => $request->nim,
            'waktu_presensi' => $waktuPresensi,
            'status' => 'Hadir'
        ]);

        try {
            if ($presensi->save()) {
                return back()->with([
                    "message" => "Presensi dengan nim {$request->nim} berhasil!",
                    "status" => true,
                ]);
            }
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Presensi dengan nim {$request->nim} Gagal!",
                "status" => false,
            ]);
        }
    }
}
