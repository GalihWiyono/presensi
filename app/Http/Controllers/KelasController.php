<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\Sesi;
use App\Models\Presensi;
use App\Models\Qrcode as ModelsQrcode;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KelasController extends Controller
{
    public function index()
    {
        Gate::allows('isDosen') ? Response::allow() : abort(403);
        return view('kelas/kelas', ['jadwal' => Jadwal::where('nip', auth()->user()->dosen->nip)->get()]);
    }

    public function show($id)
    {
        Gate::allows('isDosen') ? Response::allow() : abort(403);
        $sesiRequest = 0;
        $jadwal_kelas = Jadwal::where('id', $id)->with('kelas','kelas.mahasiswa')->first();
        $allSesi = Sesi::where(['jadwal_id' => $id])->get();

        if (request('searchPekan')) {
            $sesiRequest = request('searchPekan');
        } else {
            $sesiRequest = $allSesi->where('status', "Belum")->first()->sesi;
        }

        //mendapatkan sesi dengan status belum ketika awal membuka (sesiRequest 0 hanya untuk trigger)
        // if ($sesiRequest == 0) {
        //     $sesiRequest = $allSesi->where('status', "Belum")->first()->sesi;
        // }
        
        $activeSesiData = $allSesi->where('sesi', $sesiRequest)->first();
        $presensi = Presensi::where('sesi_id', $activeSesiData->id)->with('mahasiswa')->orderBy('nim', 'asc');
        $qrcode = "";
        
        //generate QRCode
        if ($jadwal_kelas->mulai_absen != null && $jadwal_kelas->akhir_absen) {
            $qrcode = $this->generate($jadwal_kelas, $activeSesiData);
        }

        return view('kelas/detail_kelas', [
            'detail' => $jadwal_kelas,
            'sesi' => $allSesi,
            'absen' => $presensi->paginate(8)->withQueryString(),
            'qrcode' => $qrcode,
            "sesiNow" => $sesiRequest,
            "activeSesi" => $activeSesiData,
            'anggotaKelas' => $jadwal_kelas->kelas->mahasiswa
        ]);
    }

    public function generate($jadwal_kelas, $sesi)
    {
        Gate::allows('isDosen') ? Response::allow() : abort(403);
        $random = md5(uniqid(rand(), true));
        $dataQR = ModelsQrcode::firstOrCreate([
            'jadwal_id' => $jadwal_kelas->id,
            'sesi_id' => $sesi->id,
        ], [
            'unique' => Crypt::encryptString($random),
            'jadwal_id' => $jadwal_kelas->id,
            'sesi_id' => $sesi->id,
            'tanggal' => $sesi->tanggal,
            'mulai_absen' => $jadwal_kelas->mulai_absen,
            'akhir_absen' => $jadwal_kelas->akhir_absen,
            'status' => 'Active'
        ]);

        $jsonData = json_encode($dataQR);
        $qrcode = QrCode::size('300')->generate($jsonData);

        return $qrcode;
    }

    public function updateWaktuAbsen($id, Request $request)
    {
        Gate::allows('isDosen') ? Response::allow() : abort(403);
        $jadwal_kelas = Jadwal::find($id);
        $jadwal_kelas->mulai_absen = $request->mulai_absen;
        $jadwal_kelas->akhir_absen = $request->akhir_absen;

        try {
            if ($jadwal_kelas->save()) {
                return back()->with([
                    "message" => "Berhasil mengupdate waktu mulai dan berakhir absen",
                    "status" => true,
                ]);
            }
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal mengupdate waktu mulai dan berakhir absen, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function gantiSesi($id, Request $request)
    {
        Gate::allows('isDosen') ? Response::allow() : abort(403);
        return $this->show($id, $request->sesi);
    }

    public function presence(Request $request)
    {
        Gate::allows('isDosen') ? Response::allow() : abort(403);

        try {
            $presensi = Presensi::firstOrCreate([
                'nim' => $request->nim,
                'sesi_id' => $request->sesi_id
            ], [
                'sesi_id' => $request->sesi_id,
                'nim' => $request->nim,
                'waktu_presensi' => $request->waktu_presensi,
                'status' => $request->status
            ]);

            if ($presensi->wasRecentlyCreated) {
                return back()->with([
                    "message" => "Presensi berhasil!",
                    "status" => true,
                ]);
            } else {
                return back()->with([
                    "message" => "Presensi dengan nim {$request->nim} Gagal, Error: Sudah melakukan presensi!",
                    "status" => false,
                ]);
            }
            
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Presensi dengan nim {$request->nim} Gagal, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function closePekan(Request $request)
    {
        Gate::allows('isDosen') ? Response::allow() : abort(403);
        //tutup sesi
        try {
            $sesi = Sesi::where('id', $request->sesi_id);


            $jadwal = Jadwal::find($request->jadwal_id);
            $anggotaKelas = $jadwal->kelas->anggota_kelas;
            foreach ($anggotaKelas as $mahasiswa) {
                $absen = Presensi::firstOrCreate([
                    'nim' => $mahasiswa->nim,
                    'sesi_id' => $request->sesi_id
                ], [
                    'sesi_id' => $request->sesi_id,
                    'nim' => $mahasiswa->nim,
                    'status' => "Tidak Hadir"
                ]);
            };

            $affectedRow = $sesi->update(['status' => 'Selesai']);

            return back()->with([
                "message" => "Tutup pekan berhasil!",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Tutup pekan gagal, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function editPresensi($id, Request $request)
    {
        try {

            $affectedRows = Presensi::where([
                ['sesi_id', $request->sesi_id],
                ['nim', $request->nim]
            ])->update(["waktu_presensi" => $request->waktu_presensi, "status" => $request->status]);

            return back()->with([
                "message" => "Edit Presensi Berhasil!",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Edit Presensi gagal, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function checkNim(Request $request)
    {
        if ($request->ajax()) {
            $errorMessage = "";
            $mahasiswa = Mahasiswa::where([
                ['nim', $request->nim],
                ['kelas_id', $request->kelas_id]
            ])->first();

            if ($mahasiswa == null) {
                $errorMessage = "NIM " . $request->nim . " tidak ditemukan atau berbeda kelas!";
                return response()->json(['status' => "Invalid", 'errorMessage' => $errorMessage]);
            } else {
                $presensi = Presensi::where([
                    ['sesi_id', $request->sesi_id],
                    ['nim', $request->nim]
                ])->first();

                if ($presensi != null) {
                    $errorMessage = "NIM " . $request->nim . " sudah melakukan presensi!";
                    return response()->json(['status' => "Invalid", 'errorMessage' => $errorMessage]);
                }

                $dataResponse = [
                    'nim' => $mahasiswa->nim,
                    'nama' => $mahasiswa->nama_mahasiswa,
                    'kelas' => $mahasiswa->kelas->nama_kelas
                ];
                $objectResponse = (object)$dataResponse;
                return response()->json(['data' => $objectResponse, 'status' => "Valid", 'errorMessage' => $errorMessage]);
            }
        }
    }
}
