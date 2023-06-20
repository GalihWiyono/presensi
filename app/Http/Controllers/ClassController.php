<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Presensi;
use App\Models\Sesi;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::allows('isAdmin') ? Response::allow() : abort(403);
        $kelas =  Kelas::latest();
        $anggotaKelas = new AnggotaKelas;

        if (request('search')) {
            $kelas->where('nama_kelas', 'like', '%' . request('search') . '%');
        }
        return view('academic/classes', [
            'kelas' => $kelas->paginate(7)->withQueryString(),
            'anggotaKelas' => $anggotaKelas->get()
        ]);
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
        try {
            $kelas = new Kelas([
                'nama_kelas' => $request->nama_kelas,
            ]);
            if ($kelas->save()) {
                return back()->with([
                    "message" => "Berhasil menambah data kelas",
                    "status" => true,
                ]);
            }
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal menambah data kelas, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Gate::allows('isAdmin') ? Response::allow() : abort(403);
        $anggotaKelas = AnggotaKelas::with(['mahasiswa'])->where('kelas_id', $id);
        $kelas = Kelas::find($id);
        return view('academic/class', [
            'anggota' => $anggotaKelas->paginate(7),
            'kelas' => $kelas
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
        try {
            $kelas = Kelas::where('id', $id)->first();
            $kelas->update([
                'nama_kelas' => $request->nama_kelas,
            ]);
            
            return back()->with([
                "message" => "Berhasil mengedit data class",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal mengedit data class, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            foreach (Kelas::where('id', $request->id)->get() as $deleteItem) {
                $deleteItem->delete();
            }
            return back()->with([
                "message" => "Berhasil menghapus data class",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal menghapus data class, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function detail($id, $nim)
    {
        Gate::allows('isAdmin') ? Response::allow() : abort(403);
        $sesi = new Sesi;
        $jadwal = Jadwal::with('matkul')->where('kelas_id', $id)->get();
        if(count($jadwal) == 0) {
            return back()->with([
                "message" => "Jadwal tidak tersedia pada kelas ini, mohon isi terlebih dahulu!",
                "status" => false,
            ]);
        }
        $getData = $sesi->where('jadwal_id', $jadwal->first()->id);
        
        if(request('kelas')) {
           $getData = $sesi->where('jadwal_id', request('kelas'));
        }

        $presensi = Presensi::where('nim', $nim)->get();
        return view('academic/detail_mahasiswa', [
            'jadwal' => $jadwal,
            'sesi' => $getData->paginate(7)->withQueryString(),
            'presensi' => $presensi,
            'mahasiswa' => $presensi->first()->mahasiswa
        ]);
    }
}
