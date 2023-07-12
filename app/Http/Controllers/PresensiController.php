<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\LogMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Pending;
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
        Gate::allows('isMahasiswa') ? Response::allow() : abort(403);
        try {
            $waktuPresensi = Carbon::now();
            $jadwal = Jadwal::find($request->id);
            $nim = auth()->user()->mahasiswa->nim;
            $statusPresensi = "Hadir";

            $jam_mulai = $jadwal->jam_mulai;
            $jam_berakhir = $jadwal->jam_berakhir;
            $mulai_absen = $jadwal->mulai_absen;
            $akhir_absen =  $jadwal->akhir_absen;

            //check if sesi pending
            $checkPending = Pending::where([
                'jadwal_id' => $request->id,
                'sesi_id' => $request->sesi_id,
                'status' => "Belum"
            ])->first();
            
            if ($checkPending) {
                $jam_mulai = $checkPending->jam_mulai_baru;
                $jam_berakhir = $checkPending->jam_berakhir_baru;
                $mulai_absen = $checkPending->mulai_absen_baru;
                $akhir_absen =  $checkPending->akhir_absen_baru;
            }
            
            if (!$waktuPresensi->isBetween($mulai_absen, $jam_berakhir)) {
                return back()->with([
                    "message" => "Unable to mark attendance outside the scheduled hours!",
                    "status" => false,
                ]);
            }

            if ($waktuPresensi > $akhir_absen) {
                $statusPresensi = "Terlambat";
            }

            $presensi = Presensi::firstOrCreate([
                'nim' => $nim,
                'sesi_id' => $request->sesi_id
            ], [
                'sesi_id' => $request->sesi_id,
                'jadwal_id' => $request->id,
                'nim' => $nim,
                'waktu_presensi' => $waktuPresensi,
                'status' => $statusPresensi
            ]);

            if ($presensi->wasRecentlyCreated) {

                LogMahasiswa::create([
                    'nim' => $presensi->nim,
                    'jadwal_id' => $request->id,
                    'activity' => "Presensi Pekan " . $presensi->sesi->sesi . " " . $presensi->sesi->jadwal->matkul->nama_matkul . " : $presensi->status"
                ]);

                return back()->with([
                    "message" => "Successful attendance recorded!",
                    "status" => true,
                ]);
            } else {
                return back()->with([
                    "message" => "You have already marked attendance for the schedule this week!",
                    "status" => false,
                ]);
            }
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to mark attendance, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function checkPresensi(Request $request)
    {
        Gate::allows('isMahasiswa') ? Response::allow() : abort(403);
        if ($request->ajax()) {
            $status = "Invalid";
            $errorMessage = "";

            $mahasiswa = Mahasiswa::where('user_id', auth()->user()->id)->first();
            $getData = Qrcode::find($request->id);
            if ($getData == null) {
                $errorMessage = "QR Invalid";
                return response()->json(['status' => $status, 'errorMessage' => $errorMessage]);
            }
            $dataJadwal = $getData->jadwal;
            
            //data tidak pending
            $jam_mulai = $dataJadwal->jam_mulai;
            $jam_berakhir = $dataJadwal->jam_berakhir;
            $mulai_absen = $dataJadwal->mulai_absen;
            $akhir_absen =  $dataJadwal->akhir_absen;
            $tanggal = $getData->sesi->tanggal;

            $pending = Pending::where([
                'jadwal_id' => $getData->jadwal_id,
                'sesi_id' => $getData->sesi_id
            ])->first();

            //data jika pending
            if($pending) {
                $jam_mulai = $pending->jam_mulai_baru;
                $jam_berakhir = $pending->jam_berakhir_baru;
                $mulai_absen = $pending->mulai_absen_baru;
                $akhir_absen =  $pending->akhir_absen_baru;
                $tanggal = $pending->tanggal_baru;
            }

            $QRCodeUnique = $request->unique;
            $dataUnique = $getData->unique;

            try {
                $QRCodeUnique = Crypt::decryptString($QRCodeUnique);
                $dataUnique = Crypt::decryptString($dataUnique);
            } catch (DecryptException $e) {
                $errorMessage = "Decrypt Failed, Error : " + $e;
            }

            if ($mahasiswa->kelas_id === $dataJadwal->kelas_id && $QRCodeUnique == $dataUnique && $getData->status == "Active") {
                $status = "Valid";
            }

            $dataResponse = [
                'id' => $dataJadwal->id,
                'sesi_id' => $getData->sesi_id,
                'matkul' => $dataJadwal->matkul->nama_matkul,
                'kelas' => $dataJadwal->kelas->nama_kelas,
                'dosen' => $dataJadwal->dosen->nama_dosen,
                'jam_mulai' => $jam_mulai,
                'jam_berakhir' => $jam_berakhir,
                'pekan' => $getData->sesi->sesi,
                'tanggal' => $tanggal,
                'mulai_absen' => $mulai_absen,
                'akhir_absen' => $akhir_absen,
            ];

            $objectResponse = (object)$dataResponse;

            return response()->json(['status' => $status, 'data' => $objectResponse, 'errorMessage' => $errorMessage]);
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
