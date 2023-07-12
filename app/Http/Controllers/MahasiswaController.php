<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\Kelas;
use App\Models\LogAdmin;
use App\Models\Mahasiswa;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

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
                    "message" => "Failed to create student data, NIM $request->nim already exist!",
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

            if ($mahasiswa->save()) {
                //add mahasiswa to anggota kelas
                AnggotaKelas::create([
                    'nim' => $mahasiswa->nim,
                    'kelas_id' => $mahasiswa->kelas_id
                ]);
            } else {
                return back()->with([
                    "message" => "Failed to create student data with NIM $request->nim",
                    "status" => false,
                ]);
            }

            return back()->with([
                "message" => "Successfully created student data with NIM $request->nim",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to create student data, Error: " . json_encode($th->getMessage(), true),
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
            $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

            $kelas = ($request->kelas_id) ? $request->kelas_id : null;

            $mahasiswa->update([
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'tanggal_lahir' => $request->tanggal_lahir,
                'user_id' => $request->user_id,
                'gender' => $request->gender,
                'kelas_id' => $kelas,
            ]);

            $mahasiswa->anggota_kelas()->update([
                'kelas_id' => $kelas
            ]);

            return back()->with([
                "message" => "Successfully edited student data",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to edit student data, Error: " . json_encode($th->getMessage(), true),
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
            foreach (Mahasiswa::where([
                "user_id" => $request->user_id,
                "nim" => $request->nim
            ])->get() as $deleteItem) {
                $deleteItem->delete();
            }
            User::where("id", $request->user_id)->delete();
            AnggotaKelas::where('nim', $request->nim)->delete();
            return back()->with([
                "message" => "Successfully deleted student data",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to delete student data, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            #Match The Admin Password
            if (!Hash::check($request->admin_password, auth()->user()->password)) {
                return back()->with([
                    "message" => "Admin Password Doesn't match!",
                    "status" => false,
                ]);
            }

            #Update the new Password
            User::whereId($request->user_id)->update([
                'password' => bcrypt($request->student_password)
            ]);

            return back()->with([
                "message" => "Change Password Success",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Change Password Failed, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }
}
