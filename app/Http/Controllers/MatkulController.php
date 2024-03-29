<?php

namespace App\Http\Controllers;

use App\Exports\MataKuliahExport;
use App\Imports\MataKuliahImport;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class MatkulController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::allows('isAdmin') ? Response::allow() : abort(403);
        $matkul =  MataKuliah::latest();

        if (request('search')) {
            $matkul->where('nama_matkul', 'like', '%' . request('search') . '%');
        }

        return view('academic/course', [
            "matkul" => $matkul->paginate(7)->withQueryString()
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
            $matkul = new MataKuliah([
                'nama_matkul' => $request->nama_matkul,
            ]);
            if ($matkul->save()) {
                return back()->with([
                    "message" => "Successfully created course data",
                    "status" => true,
                ]);
            }
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to create course data, Error: " . json_encode($th->getMessage(), true),
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
            $matkul = MataKuliah::where('id', $request->id)->first();
            $matkul->update([
                'nama_matkul' => $request->nama_matkul,
            ]);

            return back()->with([
                "message" => "Successfully edited course data",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to edit course data, Error: " . json_encode($th->getMessage(), true),
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
            $data = Jadwal::where('matkul_id', $request->id_matkul)->get();
            
            if (count($data) != 0) {
                return back()->with([
                    "message" => "Failed to delete the course because there are schedules that utilize this coure, please double-check the Schedule data!",
                    "status" => false,
                ]);
            }
            
            foreach (MataKuliah::where('id', $request->id_matkul)->get() as $deleteItem) {
                $deleteItem->delete();
            }

            return back()->with([
                "message" => "Successfully deleted course data",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to delete course data, Error: " . json_encode($th->getMessage(), true),
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

            Excel::import(new MataKuliahImport, $request->file('file'));

            return back()->with([
                "message" => "Upload Data Course Success",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Upload Data Course Failed" . ", Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function export() 
    {
        return Excel::download(new MataKuliahExport, 'matakuliah.xlsx');
    }
}
