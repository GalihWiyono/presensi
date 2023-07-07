<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function showChangePassword()
    {
        $user = auth()->user();
        if ($user->role == "Mahasiswa") {
            Gate::allows('isMahasiswa') ? Response::allow() : abort(403);

            return view('account/change_password', [
                'data' => $user->mahasiswa
            ]);
        }

        if ($user->role == "Dosen") {
            Gate::allows('isDosen') ? Response::allow() : abort(403);

            return view('account/change_password', [
                'data' => $user->dosen
            ]);
        }

        if ($user->role == "Admin") {
            Gate::allows('isAdmin') ? Response::allow() : abort(403);

            return view('account/change_password', [
                'data' => $user->admin
            ]);
        }
    }

    // public function showDosenChangePassword()
    // {
    //     Gate::allows('isMahasiswa') ? Response::allow() : abort(403);
    //     $user = auth()->user()->mahasiswa;
    //     return view('account/mahasiswa_changePassword', [
    //         'mahasiswa' => $user
    //     ]);
    // }

    // public function showAdminChangePassword()
    // {
    //     Gate::allows('isMahasiswa') ? Response::allow() : abort(403);
    //     $user = auth()->user()->mahasiswa;
    //     return view('account/mahasiswa_changePassword', [
    //         'mahasiswa' => $user
    //     ]);
    // }

    public function changePassword(Request $request)
    {
        try {
            #Match The Old Password
            if (!Hash::check($request->old_password, auth()->user()->password)) {
                return back()->with([
                    "message" => "Old Password Doesn't match!",
                    "status" => false,
                ]);
            }

            #Update the new Password
            User::whereId($request->id)->update([
                'password' => bcrypt($request->new_password)
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
