<?php

namespace App\Http\Controllers;

use App\Exports\DosenExport;
use App\Imports\DosenImport;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

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
        $dosen = Dosen::latest();
        
        if(request('search')) {
            $dosen->where('nama_dosen', 'like', '%' . request('search'). '%')
            ->orWhere('nip', 'like', '%' . request('search'). '%');
        } 
        return view('database/dosen', [
            "dosen" => $dosen->paginate(7)->withQueryString()
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
            $cekDosen = Dosen::where('nip', $request->nip)->first();
            if ($cekDosen) {
                return back()->with([
                    "message" => "Failed to create admin data, NIP $request->nip already exist!",
                    "status" => false,
                ]);
            }

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
                "message" => "Successfully created lecturer data with NIP $request->nip",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to create lecturer data, Error: " . json_encode($th->getMessage(), true),
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
            $dosen = Dosen::where('nip', $request->nip)->first();
            $dosen->update([
                'nama_dosen' => $request->nama_dosen,
                'tanggal_lahir' => $request->tanggal_lahir,
                'user_id' => $request->user_id,
                'gender' => $request->gender,
            ]);
            return back()->with([
                "message" => "Successfully edited lecturer data",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to edit lecturer data, Error: " . json_encode($th->getMessage(), true),
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
            $data = Jadwal::where('nip', $request->nip)->get();

            if (count($data) != 0) {
                return back()->with([
                    "message" => "Failed to delete the lecturer because there are schedules that utilize this lecturer, please double-check the Schedule data!",
                    "status" => false,
                ]);
            }

            
            foreach (Dosen::where([
                "user_id" => $request->user_id,
                "nip" => $request->nip
            ])->get() as $deleteItem) {
                $deleteItem->delete();
            }
            User::where("id", $request->user_id)->delete();
            return back()->with([
                "message" => "Successfully deleted lecturer data",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to delete lecturer data, Error: " . json_encode($th->getMessage(), true),
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
                    "message" => __("Admin Password Doesn't match"),
                    "status" => false,
                ]);
            }

            #Update the new Password
            User::whereId($request->user_id)->update([
                'password' => bcrypt($request->student_password)
            ]);

            return back()->with([
                "message" => __("Change Password Success"),
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" =>  __("Change Password Failed")." Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function import(Request $request) 
    {
        try {
            $whitelistType = array('xlsx','xls','csv');
            $extension = $request->file('file')->extension();
            
            if(!in_array($extension, $whitelistType)) {
                return back()->with([
                    "message" => __("The uploaded file type is not supported"),
                    "status" => false,
                ]);
            }

            Excel::import(new DosenImport, $request->file('file'));

            return back()->with([
                "message" => __("Upload Data Lecturers Success"),
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => __("Upload Data Lecturers Failed") .", Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function export() 
    {
        return Excel::download(new DosenExport, 'dosen.xlsx');
    }
}
