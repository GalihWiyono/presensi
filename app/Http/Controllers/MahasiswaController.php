<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::allows('isAdmin') ? Response::allow() : abort(403);
        $mahasiswa = Mahasiswa::with('kelas')->latest();

        if (request('search')) {
            $mahasiswa->where('nama_mahasiswa', 'like', '%' . request('search') . '%')
                ->orWhere('nim', 'like', '%' . request('search') . '%');
        }
        $kelas = Kelas::all();
        return view('database/mahasiswa', [
            'mahasiswa' => $mahasiswa->paginate(7),
            'kelas' => $kelas
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
            $cekMahasiswa = Mahasiswa::where('nim', $request->nim)->first();
            if ($cekMahasiswa) {
                return back()->with([
                    "message" => "Gagal membuat data mahasiswa, NIM $request->nim sudah ada!",
                    "status" => false,
                ]);
            }

            $mahasiswa = new Mahasiswa([
                "nim" => $request->nim,
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'tanggal_lahir' => $request->tanggal_lahir,
                'gender' => $request->gender,
                'kelas_id' => $request->kelas_id
            ]);

            User::create([
                'username' => $request->nim,
                'password' => bcrypt("mahasiswa"),
                'role' => "Mahasiswa",
            ]);

            $mahasiswa->user_id = User::where('username', $request->nim)->first()->id;
            $mahasiswa->save();

            return back()->with([
                "message" => "Berhasil membuat data mahasiswa dengan NIM $request->nim",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal membuat data mahasiswa, Error: " . json_encode($th->getMessage(), true),
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
        //
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
    public function update(Request $request)
    {
        try {
            Mahasiswa::where('nim', $request->nim)->update([
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'tanggal_lahir' => $request->tanggal_lahir,
                'user_id' => $request->user_id,
                'gender' => $request->gender,
                'kelas_id' => $request->kelas_id,
            ]);
            return back()->with([
                "message" => "Berhasil mengedit data mahasiswa",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal mengedit data mahasiswa, Error: " . json_encode($th->getMessage(), true),
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
            Mahasiswa::where([
                "user_id" => $request->user_id,
                "nim" => $request->nim
            ])->delete();
            User::find($request->user_id)->delete();
            return back()->with([
                "message" => "Berhasil menghapus data mahasiswa",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal menghapus data mahasiswa, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }
}
