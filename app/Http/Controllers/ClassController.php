<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\Kelas;
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
            Kelas::where('id', $id)->update([
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
            Kelas::find($request->id)->delete();
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
}
