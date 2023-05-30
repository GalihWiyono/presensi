<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\Presensi;
use App\Models\Sesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;

class JadwalMahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::allows('isMahasiswa') ? Response::allow() : abort(403);
        $kelas =  auth()->user()->mahasiswa->kelas;
        $jadwal = Jadwal::with('matkul', 'dosen')->where('kelas_id', $kelas->id);
        return view('jadwal/jadwal', ['jadwal' => $jadwal->paginate(8) , 'kelas' => $kelas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Gate::allows('isMahasiswa') ? Response::allow() : abort(403);
        $mahasiswa = auth()->user()->mahasiswa;
        $jadwal_kelas = Jadwal::find($id);
        $sesi = Sesi::where('jadwal_id', $id);
        $presensi = Presensi::where('nim', $mahasiswa->nim)->get();

        return view('jadwal/detail_jadwal', [
            'detail' => $jadwal_kelas,
            'sesi' => $sesi->paginate(9),
            'presensi' => $presensi
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
