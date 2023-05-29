<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\Presensi;
use App\Models\Qrcode;
use Carbon\Carbon;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::allows('isMahasiswa') ? Response::allow() : abort(403);
        return view('presensi/presensi', ['mahasiswa' => Mahasiswa::where('user_id', auth()->user()->id)->first()]);
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
        $waktuPresensi = Carbon::now()->format('H:i:s');
        $statusPresensi = "Hadir";
        $nim = auth()->user()->mahasiswa->nim;

        if ($waktuPresensi > $request->akhir_absen) {
            $statusPresensi = "Terlambat";
        }

        $presensi = new Presensi([
            'jadwal_id' => $request->id,
            'nim' => $nim,
            'waktu_presensi' => $waktuPresensi,
            'status' => $statusPresensi
        ]);

        try {
            if ($presensi->save()) {
                return back()->with([
                    "message" => "Presensi berhasil!",
                    "status" => true,
                ]);
            }
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Presensi Gagal, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function checkPresensi(Request $request)
    {
        if ($request->ajax()) {
            $status = "Invalid";
            $errorMessage = "";

            $mahasiswa = Mahasiswa::where('user_id', auth()->user()->id)->first();
            $getData = Qrcode::find($request->id);
            $dataJadwal = $getData->jadwal;
            $QRCodeUnique = $request->unique;
            $dataUnique = $getData->unique;

            try {
                $QRCodeUnique = Crypt::decryptString($QRCodeUnique);
                $dataUnique = Crypt::decryptString($dataUnique);
            } catch (DecryptException $e) {
                $errorMessage = "Decrypt Failed, Error : " + $e;
            }

            if($mahasiswa->kelas_id === $dataJadwal->kelas_id && $QRCodeUnique == $dataUnique && $getData->status == "Active") {
                $status = "Valid";
            }

            $dataResponse = [
                'id' => $dataJadwal->id,
                'matkul' => $dataJadwal->matkul->nama_matkul,
                'kelas' => $dataJadwal->kelas->nama_kelas,
                'dosen' => $dataJadwal->dosen->nama_dosen,
                'jam_mulai' => $dataJadwal->jam_mulai,
                'jam_berakhir' => $dataJadwal->jam_berakhir,
                'pekan' => $getData->sesi->sesi,
                'tanggal' => $getData->tanggal,
                'mulai_absen' => $getData->mulai_absen,
                'akhir_absen' => $getData->akhir_absen,
            ];

            $objectResponse = (object)$dataResponse;

            return response()->json(['status'=>$status, 'data' => $objectResponse, 'errorMessage' => $errorMessage]);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
