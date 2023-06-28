<?php

namespace App\Http\Controllers;

use App\Models\Pending;
use Carbon\Carbon;
use App\Models\Presensi;
use App\Models\Jadwal;
use App\Models\Sesi;
use App\Models\Qrcode as ModelsQrcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Auth\Access\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PendingController extends Controller
{
    public function showPending() {
        Gate::allows('isDosen') ? Response::allow() : abort(403);
        $user = auth()->user()->dosen;
        $pendingWeek = Pending::where([
            'nip' => $user->nip,
            'status' => "Belum"
        ])->with('jadwal', 'jadwal.kelas','jadwal.matkul');
        return view('kelas/pending_kelas', [
            'pendingWeek' => $pendingWeek->paginate(8),
        ]);
    }

    public function updateDate(Request $request) {
        Gate::allows('isDosen') ? Response::allow() : abort(403);
        try {
            $pending = Pending::find($request->id);
            $pending->update([
                'tanggal_baru' => $request->tanggal_baru,
                'mulai_absen_baru' => $request->mulai_absen,
                'akhir_absen_baru' => $request->akhir_absen,
            ]);

            return back()->with([
                "message" => "Update Pending Week Success!",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Update Pending Week Failed, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function showDetailPending($id) {
        Gate::allows('isDosen') ? Response::allow() : abort(403);
        $pendingData = Pending::find($id);
        $jadwal_kelas =  $pendingData->jadwal;
        $activeSesiData = $pendingData->sesi;
        $dateToday = Carbon::now()->toDateString();

        $presensi = Presensi::where('sesi_id', $activeSesiData->id)->with('mahasiswa')->orderBy('nim', 'asc');
        $qrcode = "";
        $sesiPending = Pending::where('jadwal_id', $id)->get();

        //generate QRCode
        if ($jadwal_kelas->mulai_absen != null && $jadwal_kelas->akhir_absen) {
            $qrcode = $this->generate($jadwal_kelas, $activeSesiData);
        }

        $data = $jadwal_kelas->kelas->mahasiswa;
        $dataPush = [];
        foreach ($data as $anggota) {
            $addData = (object) [
                'nim' => $anggota->nim,
                'nama_mahasiswa' => $anggota->nama_mahasiswa,
                'status' => "Tidak Hadir"
            ];
            (object) array_push($dataPush, $addData);
        }

        foreach ($data as $key => $value) {
            foreach ($presensi->get() as $dataPresensi) {
                if ($dataPresensi->nim == $value->nim) {
                    $dataPush[$key]->status = $dataPresensi->status;
                }
            }
        }
        $dataPush = collect($dataPush);

        $status = "Active";

        if($pendingData->status != "Belum") {
            $status = "Inactive";
        }

        if($pendingData->tanggal_baru == null) {
            $status = "Inactive";
        } else if($pendingData->tanggal_baru != $dateToday) {
            $status = "Inactive";
        }

        if($pendingData->mulai_absen_baru == null || $pendingData->akhir_absen_baru == null) {
            $status = "Inactive";
        }

        return view('kelas/detail_pending', [
            'detail' => $jadwal_kelas,
            'pendingData' => $pendingData,
            'absen' => $presensi->paginate(8)->withQueryString(),
            'qrcode' => $qrcode,
            "activeSesi" => $activeSesiData, 
            'anggotaKelas' => $dataPush,
            'status' => $status,
        ]);
    }

    public function closePendingWeek(Request $request) {
        Gate::allows('isDosen') ? Response::allow() : abort(403);
        //tutup sesi
        try {
            $sesi = Sesi::where('id', $request->sesi_id)->first();

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

            $sesi->update(['status' => 'Selesai']);

            $pendingSesi = Pending::where([
                'sesi_id' => $request->sesi_id,
                'jadwal_id' => $request->jadwal_id
            ])->first();

            $pendingSesi->update(['status' => 'Selesai']);

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

    public function checkPending(Request $request) {
        $userLoggedIn = auth()->user()->dosen;
        if ($request->ajax()) {
            $errorMessage = "";
            $thereIsPending = false;

            $pendingData = Pending::where([
                ['nip', $userLoggedIn->nip],
                ['status', "Belum"]
            ])->get();

            $thereIsPending = (count($pendingData) > 0) ? true : false;
            return response()->json(['data' => $pendingData, 'status' => $thereIsPending, 'errorMessage' => $errorMessage]);
        }
    }
}
