<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\Jadwal;
use App\Models\LogDosen;
use App\Models\Mahasiswa;
use App\Models\Pending;
use App\Models\Sesi;
use App\Models\Presensi;
use App\Models\Qrcode as ModelsQrcode;
use Carbon\Carbon;
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
        $jadwal_kelas = Jadwal::where('id', $id)->with('kelas', 'kelas.mahasiswa')->first();
        $startDate = Carbon::parse($jadwal_kelas->tanggal_mulai)->format('Y-m-d');
        $dateToday = Carbon::now()->addDays(7)->toDateString();
        $allSesi = Sesi::where(['jadwal_id' => $id])->whereBetween('tanggal', [$startDate, $dateToday])->get();

        if (request('week')) {
            $sesiRequest = request('week');
        } else {
            $sesiRequest = $allSesi->where('status', "Belum")->first()->sesi;
        }

        $activeSesiData = $allSesi->where('sesi', $sesiRequest)->first();
        if ($activeSesiData == null) {
            abort(404);
        }
        $dateToday = Carbon::now()->toDateString();
        $sesiToday = $allSesi->where('tanggal', $dateToday)->first();
        $presensi = Presensi::where('sesi_id', $activeSesiData->id)->with('mahasiswa')->orderBy('nim', 'asc');
        $qrcode = "";
        $sesiPending = Pending::where([
            'jadwal_id' => $id,
            'status' => "Belum"
        ])->get();
        
        //generate QRCode
        if ($jadwal_kelas->mulai_absen != null && $jadwal_kelas->akhir_absen != null && $sesiToday != null) {
            $qrcode = $this->generate($jadwal_kelas, $sesiToday);
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

        if ($sesiToday == null) {
            $status = "Inactive";
        } else if ($sesiToday->id != $activeSesiData->id) {
            $status = "Inactive";
        }

        return view('kelas/detail_kelas', [
            'detail' => $jadwal_kelas,
            'sesi' => $allSesi,
            'absen' => $presensi->paginate(8)->withQueryString(),
            'qrcode' => $qrcode,
            "sesiNow" => $sesiRequest,
            "activeSesi" => $activeSesiData,
            'anggotaKelas' => $dataPush,
            'sesiToday' => $sesiToday,
            'status' => $status,
            'sesiPending' => $sesiPending
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
                    "message" => __("Successfully updated the start and end time of attendance"),
                    "status" => true,
                ]);
            }
        } catch (\Throwable $th) {
            return back()->with([
                "message" => __("Failed to update the start and end time of attendance").", Error: " . json_encode($th->getMessage(), true),
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
                'jadwal_id' => $request->jadwal_id,
                'nim' => $request->nim,
                'waktu_presensi' => $request->waktu_presensi,
                'status' => $request->status
            ]);

            if ($presensi->wasRecentlyCreated) {
                $loggedIn = auth()->user();
                LogDosen::create([
                    'nip' => $loggedIn->dosen->nip,
                    'nim' => $presensi->nim,
                    'kelas_id' => $presensi->sesi->jadwal->kelas_id,
                    'activity' => "Menambah Presensi: Kelas " . $presensi->sesi->jadwal->kelas->nama_kelas . " NIM $presensi->nim Pekan " . $presensi->sesi->sesi . " Status $presensi->status"
                ]);

                return back()->with([
                    "message" => __("Marking attendance success"),
                    "status" => true,
                ]);
            } else {
                return back()->with([
                    "message" => __("Marking attendance failed, Error: Already marked attendance"),
                    "status" => false,
                ]);
            }
        } catch (\Throwable $th) {
            return back()->with([
                "message" => __("Marking attendance failed "). ", Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function presensiOnline(Request $request)
    {
        try {
            $currentTime = Carbon::now()->format('H:i:s');
            $data = [];

            for ($i = 0; $i < count($request->nim); $i++) {
                $addData = (object) [
                    'nim' => $request->nim[$i],
                    'status' => $request->status[$i],
                    'waktu_presensi' => ($request->status[$i] == "Izin" || $request->status[$i] == "Tidak Hadir") ? null : $currentTime
                ];
                (object) array_push($data, $addData);
            }

            $data = collect($data);

            foreach ($data as $presensi) {
                $absen = Presensi::firstOrCreate([
                    'nim' => $presensi->nim,
                    'sesi_id' => $request->sesi_id
                ], [
                    'sesi_id' => $request->sesi_id,
                    'jadwal_id' => $request->jadwal_id,
                    'nim' => $presensi->nim,
                    'waktu_presensi' => $presensi->waktu_presensi,
                    'status' => $presensi->status
                ]);

                if (!$absen->wasRecentlyCreated) {
                    $absen->update([
                        'sesi_id' => $request->sesi_id,
                        'nim' => $presensi->nim,
                        'waktu_presensi' => $presensi->waktu_presensi,
                        'status' => $presensi->status
                    ]);
                }
            }

            return back()->with([
                "message" => __("Marking online attendance success"),
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => __("Marking online attendance failed")." , Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function closePekan(Request $request)
    {
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
                    'jadwal_id' => $request->jadwal_id,
                    'nim' => $mahasiswa->nim,
                    'status' => "Tidak Hadir"
                ]);
            };

            $sesi->update(['status' => 'Selesai']);
            $qrcode = ModelsQrcode::where([
                'jadwal_id' => $request->jadwal_id,
                'sesi_id' => $request->sesi_id
            ])->first();

            $qrcode->update(['status' => 'Inactive']);

            $loggedIn = auth()->user();
            LogDosen::create([
                'nip' => $loggedIn->dosen->nip,
                'kelas_id' => $sesi->jadwal->kelas_id,
                'affected' => 'Kelas',
                'activity' => "Menutup Pekan $sesi->sesi pada Mata Kuliah " . $sesi->jadwal->matkul->nama_matkul . " Kelas " . $sesi->jadwal->kelas->nama_kelas . ": Manual"
            ]);

            return back()->with([
                "message" => __("Successfully close week"),
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => __("Failed to close week").", Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function editPresensi($id, Request $request)
    {
        try {

            $presensi = Presensi::where([
                ['sesi_id', $request->sesi_id],
                ['nim', $request->nim]
            ])->first();

            $presensi->update(["waktu_presensi" => $request->waktu_presensi, "status" => $request->status]);

            $loggedIn = auth()->user();
            LogDosen::create([
                'nip' => $loggedIn->dosen->nip,
                'nim' => $presensi->nim,
                'kelas_id' => $presensi->sesi->jadwal->kelas_id,
                'affected' => 'Mahasiswa',
                'activity' => "Mengubah Presensi: Kelas " . $presensi->sesi->jadwal->kelas->nama_kelas . " NIM $presensi->nim Pekan " . $presensi->sesi->sesi . " Status $presensi->status"
            ]);

            return back()->with([
                "message" => __("Successfully edited presence"),
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => __("Failed to edit presence").", Error: " . json_encode($th->getMessage(), true),
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
                $errorMessage = "NIM " . $request->nim . __("Not found or different class");
                return response()->json(['status' => "Invalid", 'errorMessage' => $errorMessage]);
            } else {
                $presensi = Presensi::where([
                    ['sesi_id', $request->sesi_id],
                    ['nim', $request->nim]
                ])->first();

                if ($presensi != null) {
                    $errorMessage = "NIM " . $request->nim . __("already mark attendace");
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

    public function pendingPekan(Request $request)
    {
        try {
            $user = auth()->user()->dosen->nip;
            $sesi = Sesi::find($request->sesi_id);

            $sesiPending = Pending::firstOrCreate([
                'jadwal_id' => $request->jadwal_id,
                'sesi_id' => $request->sesi_id,
            ], [
                'nip' => $user,
                'jadwal_id' => $sesi->jadwal_id,
                'sesi' => $sesi->sesi,
                'tanggal' => $sesi->tanggal,
                'status' => "Belum"
            ]);

            $sesi->update([
                'status' => "Pending"
            ]);

            return back()->with([
                "message" => __("Pending Week Success"),
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => __("Pending Week Failed").", Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }
}
