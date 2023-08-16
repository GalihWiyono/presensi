<?php

namespace App\Http\Controllers;

use App\Exports\AdminExport;
use App\Imports\AdminImport;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

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
        $loggedIn = auth()->user()->admin;

        if (request('search')) {
            $admin->where('nama_admin', 'like', '%' . request('search') . '%')
                ->orWhere('nip', 'like', '%' . request('search') . '%');;
        }

        return view('database/admin', [
            "admin" => $admin->paginate(7)->withQueryString(),
            'userLoggedIn' => $loggedIn
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
            $cekAdmin = Admin::where('nip', $request->nip)->first();
            if ($cekAdmin) {
                return back()->with([
                    "message" => "Failed to create admin data, NIP $request->nip already exist!",
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
                "message" => "Successfully created admin data with NIP $request->nip",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to create admin data, Error: " . json_encode($th->getMessage(), true),
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
            $admin = Admin::where('nip', $request->nip)->first();
            $admin->update([
                'nama_admin' => $request->nama_admin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'user_id' => $request->user_id,
                'gender' => $request->gender,
            ]);
            return back()->with([
                "message" => "Successfully edited admin data",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to edit admin data, Error: " . json_encode($th->getMessage(), true),
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
            foreach (Admin::where([
                "user_id" => $request->user_id,
                "nip" => $request->nip
            ])->get() as $deleteItem) {
                $deleteItem->delete();
            }
            User::where("id", $request->user_id)->delete();

            return back()->with([
                "message" => "Successfully deleted admin data",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to delete admin data, Error: " . json_encode($th->getMessage(), true),
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
                "message" => __("Change Password Failed").", Error: " . json_encode($th->getMessage(), true),
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

            Excel::import(new AdminImport, $request->file('file'));

            return back()->with([
                "message" => __("Upload Data Admin Success"),
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => __("Upload Data Admin Failed").", Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function export() 
    {
        return Excel::download(new AdminExport, 'admin.xlsx');
    }
}
