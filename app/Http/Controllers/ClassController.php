<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\LogAdmin;
use App\Models\LogDosen;
use App\Models\Mahasiswa;
use App\Models\Presensi;
use App\Models\Sesi;
use Carbon\Carbon;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facade\Pdf;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::allows('isAdmin') ? Response::allow() : abort(403);
        $kelas =  Kelas::latest();
        $anggotaKelas = new AnggotaKelas;

        if (request('search')) {
            $kelas->where('nama_kelas', 'like', '%' . request('search') . '%');
        }
        return view('academic/classes', [
            'kelas' => $kelas->paginate(7)->withQueryString(),
            'anggotaKelas' => $anggotaKelas->get()
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
            $kelas = new Kelas([
                'nama_kelas' => $request->nama_kelas,
            ]);
            if ($kelas->save()) {
                return back()->with([
                    "message" => "Successfully created class data",
                    "status" => true,
                ]);
            }
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to create class data, Error: " . json_encode($th->getMessage(), true),
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
        Gate::allows('isAdmin') ? Response::allow() : abort(403);
        $mahasiswa = Mahasiswa::where('kelas_id', $id);

        if (request('search')) {
            $mahasiswa->where('nama_mahasiswa', 'like', '%' . request('search') . '%');
        }

        $kelas = Kelas::find($id);
        return view('academic/class', [
            'anggota' => $mahasiswa->paginate(7),
            'kelas' => $kelas
        ]);
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
        try {
            $kelas = Kelas::where('id', $id)->first();
            $kelas->update([
                'nama_kelas' => $request->nama_kelas,
            ]);

            return back()->with([
                "message" => "Successfully edited class data",
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
            $data = Jadwal::where('kelas_id', $request->id)->get();
            
            if (count($data) != 0) {
                return back()->with([
                    "message" => "Failed to delete the class because there are schedules that utilize this class, please double-check the Schedule data!",
                    "status" => false,
                ]);
            }

            foreach (Kelas::where('id', $request->id)->get() as $deleteItem) {
                $deleteItem->delete();
            }
            return back()->with([
                "message" => "Delete Class Success",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to delete class, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function detail($id, $nim)
    {
        Gate::allows('isAdmin') ? Response::allow() : abort(403);
        $sesi = new Sesi;
        $jadwal = Jadwal::with('matkul')->where('kelas_id', $id)->get();
        if (count($jadwal) == 0) {
            return back()->with([
                "message" => "The schedule is not available for this class, please add it first!",
                "status" => false,
            ]);
        }
        $getData = $sesi->where('jadwal_id', $jadwal->first()->id);

        if (request('kelas')) {
            $getData = $sesi->where('jadwal_id', request('kelas'));
        }

        $presensi = Presensi::where('nim', $nim)->get();
        return view('academic/detail_mahasiswa', [
            'jadwal' => $jadwal,
            'sesi' => $getData->paginate(7)->withQueryString(),
            'presensi' => $presensi,
            'mahasiswa' => $presensi->first()->mahasiswa
        ]);
    }

    public function updatePresensi($id, $nim, Request $request)
    {
        try {
            $presensi = Presensi::where("id", $request->id)->first();
            $presensi->update([
                'status' => $request->status,
                'waktu_presensi' => $request->waktu_presensi
            ]);

            $loggedIn = auth()->user();
            LogAdmin::create([
                'nip' => $loggedIn->admin->nip,
                'affected' => 'Presensi',
                'activity' => "Mengubah data Presensi: $presensi->nim pada Jadwal " . $presensi->sesi->jadwal->matkul->nama_matkul . " Status : $presensi->status"
            ]);

            LogDosen::create([
                'nip' => $presensi->sesi->jadwal->nip,
                'nim' => $presensi->nim,
                'kelas_id' => $presensi->sesi->jadwal->kelas_id,
                'affected' => 'Mahasiswa',
                'activity' => "Admin " . $loggedIn->admin->nip . "=> Mengubah Presensi: Kelas " . $presensi->sesi->jadwal->kelas->nama_kelas . " NIM $presensi->nim Pekan " . $presensi->sesi->sesi . " Status $presensi->status"
            ]);

            return back()->with([
                "message" => "Successfully edited attendance data",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Failed to edit attendance data, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $errorMessage = "";
            $status = "Valid";
            $presensi = Presensi::where([
                ['nim', $request->nim],
                ['sesi_id', $request->sesi_id]
            ])->first();

            $dataResponse = [
                'nim' => $presensi->nim,
                'status' => $presensi->status,
                'waktu_presensi' => Carbon::parse($presensi->waktu_presensi)->format('H:i'),
                'id' => $presensi->id,
                'nama_mahasiswa' => $presensi->mahasiswa->nama_mahasiswa
            ];
            $objectResponse = (object)$dataResponse;
            return response()->json(['data' => $objectResponse, 'status' => $status, 'errorMessage' => $errorMessage]);
        }
    }

    public function generatePdf($id)
    {
        try {
            $jadwal = Jadwal::where('kelas_id', $id)->get();
            $anggotaKelas = AnggotaKelas::with('mahasiswa')->where('kelas_id', $id)->get();
            $jadwal_id = [];

            foreach ($jadwal as $item) {
                $jadwal_id[] = $item->id;
            }

            $presensi = Presensi::whereIn('jadwal_id', $jadwal_id)->orderBy('sesi_id')->get();

            $data = [];

            foreach ($anggotaKelas as $mahasiswa) {
                $dataMahasiswa = $presensi->where('nim', $mahasiswa->nim);
                $totalKompensasi = 0;
                $sp = "-";

                //get kompen tidak hadir
                foreach ($jadwal as $dataJadwal) {
                    foreach ($presensi as $dataPresensi) {
                        if ($dataPresensi->jadwal_id == $dataJadwal->id && $mahasiswa->nim == $dataPresensi->nim) {
                            if ($dataPresensi->status == "Tidak Hadir") {
                                $akhir_absen = Carbon::parse($dataJadwal->jam_berakhir);
                                $mulai_absen = Carbon::parse($dataJadwal->jam_mulai);
                                $selisih = $akhir_absen->diffInMinutes($mulai_absen);
                                $totalKompensasi += $selisih;
                            }

                            if ($dataPresensi->status == "Terlambat") {
                                $dataAkhirAbsen = ($dataJadwal->akhir_absen == null) ? $dataJadwal->jam_mulai : $dataJadwal->akhir_absen;
                                $akhir_absen = Carbon::parse($dataAkhirAbsen);
                                $jam_absen = Carbon::parse($dataPresensi->waktu_presensi);
                                $selisih = $akhir_absen->diffInMinutes($jam_absen);
                                $totalKompensasi += $selisih;
                            }
                        }
                    }
                }

                if ($totalKompensasi >= 750 && $totalKompensasi < 1500) {
                    $sp = "1";
                }

                if ($totalKompensasi >= 1500 && $totalKompensasi < 1850) {
                    $sp = "2";
                }

                if ($totalKompensasi >= 1850) {
                    $sp = "3";
                }

                $addData = (object)[
                    'nim' => $mahasiswa->mahasiswa->nim,
                    'nama_mahasiswa' => $mahasiswa->mahasiswa->nama_mahasiswa,
                    'hadir' => $dataMahasiswa->where('status', "Hadir")->count(),
                    'terlambat' => $dataMahasiswa->where('status', "Terlambat")->count(),
                    'izin' => $dataMahasiswa->where('status', "Izin")->count(),
                    'tidakHadir' => $dataMahasiswa->where('status', "Tidak Hadir")->count(),
                    'total_kompensasi' => $totalKompensasi,
                    'sp' => $sp
                ];
                (object) array_push($data, $addData);
            }

            // for ($i = 0; $i < 4; $i++) {
            //     $data = array_merge($data, $data);
            // }

            $data = collect($data);

            // dd($data);
            $pdf = Pdf::loadView('academic/class-pdf', [
                'data' => $data,
                'jadwal' => $jadwal
            ]);
            $pdf->setPaper('a4', 'landscape');
            return $pdf->stream('Kompensasi Kelas ' . $jadwal->first()->kelas->nama_kelas . '.pdf');
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "The class does not have a schedule, please make sure to add a schedule first!",
                "status" => false,
            ]);
        }
    }
}
