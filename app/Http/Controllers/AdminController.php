<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::allows('isAdmin') ? Response::allow() : abort(403);
        $admin =  Admin::latest();
        
        if(request('search')) {
            $admin->where('nama_admin', 'like', '%' . request('search'). '%')
            ->orWhere('nip', 'like', '%' . request('search'). '%');;
        } 

        return view('database/admin', [
            "admin" => $admin->paginate(7)->withQueryString()
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
            $cekAdmin = Admin::where('nim', $request->nim)->first();
            if ($cekAdmin) {
                return back()->with([
                    "message" => "Gagal membuat data admin, NIP $request->nip sudah ada!",
                    "status" => false,
                ]);
            }

            $admin = new Admin([
                "nip" => $request->nip,
                'nama_admin' => $request->nama_admin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'gender' => $request->gender,
            ]);

            User::create([
                'username' => $request->nip,
                'password' => bcrypt("admin"),
                'role' => "Admin",
            ]);

            $admin->user_id = User::where('username', $request->nip)->first()->id;
            $admin->save();

            return back()->with([
                "message" => "Berhasil membuat data admin dengan NIP $request->nip",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal membuat data admin, Error: " . json_encode($th->getMessage(), true),
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
            Admin::where('nip', $request->nip)->update([
                'nama_admin' => $request->nama_admin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'user_id' => $request->user_id,
                'gender' => $request->gender,
            ]);
            return back()->with([
                "message" => "Berhasil mengedit data admin",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal mengedit data admin, Error: " . json_encode($th->getMessage(), true),
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
            Admin::where([
                "user_id" => $request->user_id,
                "nip" => $request->nip
            ])->delete();
            User::find($request->user_id)->delete();
            return back()->with([
                "message" => "Berhasil menghapus data admin",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal menghapus data admin, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }
}
