<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::allows('isAdmin') ? Response::allow() : abort(403);
        $dosen = Dosen::all();
        return view('database/dosen', [
            "dosen" => $dosen
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
            $dosen = new Dosen([
                "nip" => $request->nip,
                'nama_dosen' => $request->nama_dosen,
                'tanggal_lahir' => $request->tanggal_lahir,
                'gender' => $request->gender,
            ]);

            User::create([
                'username' => $request->nip,
                'password' => bcrypt("dosen"),
                'role' => "Dosen",
            ]);

            $dosen->user_id = User::where('username', $request->nip)->first()->id;
            $dosen->save();

            return back()->with([
                "message" => "Berhasil membuat data dosen dengan NIP $request->nip",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal membuat data dosen, Error: " . json_encode($th->getMessage(), true),
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
            Dosen::where('nip', $request->nip)->update([
                'nama_dosen' => $request->nama_dosen,
                'tanggal_lahir' => $request->tanggal_lahir,
                'user_id' => $request->user_id,
                'gender' => $request->gender,
            ]);
            return back()->with([
                "message" => "Berhasil mengedit data dosen",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal mengedit data dosen, Error: " . json_encode($th->getMessage(), true),
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
            Dosen::where([
                "user_id" => $request->user_id,
                "nip" => $request->nip
            ])->delete();
            User::find($request->user_id)->delete();
            return back()->with([
                "message" => "Berhasil menghapus data dosen",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal menghapus data dosen, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }
}
