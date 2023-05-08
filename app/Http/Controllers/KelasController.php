<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Sesi;
use App\Models\Presensi;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
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

    public function show($id, $sesiRequest = 0)
    {
        Gate::allows('isDosen') ? Response::allow() : abort(403);
        $jadwal_kelas = Jadwal::find($id);
        $sesi = Sesi::where(['jadwal_id' => $id])->get();

        if ($sesiRequest == 0) {
            $sesiRequest = $sesi->where('status', "belum")->first()->sesi;
        }

        $presensi = $sesi->where('sesi', $sesiRequest)->first()->presensi->sortBy('nim');
        $qrcode = $this->generate($jadwal_kelas);

        return view('kelas/detail_kelas', [
            'detail' => $jadwal_kelas,
            'sesi' => $sesi,
            'absen' => $presensi,
            'qrcode' => $qrcode,
            "sesiNow" => $sesiRequest
        ]);
    }

    public function generate($jadwal_kelas)
    {
        Gate::allows('isDosen') ? Response::allow() : abort(403);
        $random = md5(uniqid(rand(), true));
        $dataKelas = [
            'uniq' => $random,
            'id' => $jadwal_kelas->id,
            'mataKuliah' => $jadwal_kelas->matkul->nama_matkul,
            'kelas' => $jadwal_kelas->kelas->nama_kelas,
            'dosen' => $jadwal_kelas->dosen->nama_dosen,
            'hari' => $jadwal_kelas->hari,
            'jam_mulai' => $jadwal_kelas->jam_mulai,
            'jam_berakhir' => $jadwal_kelas->jam_berakhir,
            'mulai_absen' => $jadwal_kelas->mulai_absen,
            'akhir_absen' => $jadwal_kelas->akhir_absen
        ];

        $jsonData = json_encode($dataKelas);
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
                "message" => "Gagal mengupdate waktu mulai dan berakhir absen, Error: ".json_encode($th->getMessage(), true),
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
        $waktuPresensi = date("H:i:s");
        $presensi = new Presensi([
            'jadwal_id' => $request->jadwal_id,
            'nim' => $request->nim,
            'waktu_presensi' => $waktuPresensi,
            'status' => 'Hadir'
        ]);

        try {
            if ($presensi->save()) {
                return back()->with([
                    "message" => "Presensi dengan nim {$request->nim} berhasil!",
                    "status" => true,
                ]);
            }
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Presensi dengan nim {$request->nim} Gagal, Error: ".json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }
}
