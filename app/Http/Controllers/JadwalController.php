<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\Presensi;
use App\Models\Sesi;
use ArrayObject;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facade\Pdf;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::allows('isAdmin') ? Response::allow() : abort(403);

        $jadwal = Jadwal::with(['matkul', 'dosen', 'kelas'])->latest();
        $matkul = new MataKuliah;
        $kelas = new Kelas;
        $dosen = new Dosen;

        if (request('search')) {

            $jadwal->when(request('filter') == 'Course', function ($q) use ($matkul) {
                $dataLoop = [];
                $data = $matkul->where('nama_matkul', 'like', '%' . request('search') . '%')->get();
                foreach ($data as $item) {
                    $dataLoop[] = $item->id;
                }
                return $q->whereIn('matkul_id', $dataLoop);
            });
            $jadwal->when(request('filter') == 'Class', function ($q) use ($kelas) {
                $dataLoop = [];
                $data = $kelas->where('nama_kelas', 'like', '%' . request('search') . '%')->get();
                foreach ($data as $item) {
                    $dataLoop[] = $item->id;
                }
                return $q->whereIn('kelas_id', $dataLoop);
            });
            $jadwal->when(request('filter') == 'Lecture', function ($q) use ($dosen) {
                $dataLoop = [];
                $data = $dosen->where('nama_dosen', 'like', '%' . request('search') . '%')->get();
                foreach ($data as $item) {
                    $dataLoop[] = $item->nip;
                }
                return $q->whereIn('nip', $dataLoop);
            });
        }

        return view('academic/schedule', [
            'jadwal' => $jadwal->paginate(7),
            'matkul' => $matkul->get(),
            'kelas' => $kelas->get(),
            'dosen' => $dosen->get(),
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
            $jadwal = new Jadwal([
                "matkul_id" => $request->matkul_id,
                'kelas_id' => $request->kelas_id,
                'nip' => $request->dosen_id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'jam_mulai' => $request->jam_mulai,
                'jam_berakhir' => $request->jam_berakhir
            ]);

            if ($jadwal->save()) {
                $jadwal = $jadwal->fresh();

                for ($i = 1; $i < 19; $i++) {
                    $this->generateSesi($jadwal, $i);
                }

                return back()->with([
                    "message" => "Berhasil membuat data jadwal",
                    "status" => true,
                ]);
            }
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal membuat jadwal, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function generateSesi($jadwal, $sesi)
    {
        $sesi = Sesi::create([
            'jadwal_id' => $jadwal->id,
            'sesi' => $sesi,
            'tanggal' => $jadwal->tanggal_mulai->addDays(($sesi - 1) * 7),
            'status' => 'Belum'
        ]);
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
            $jadwal = Jadwal::where('id', $request->id)->first();
            $jadwal->update([
                "matkul_id" => $request->matkul_id,
                'kelas_id' => $request->kelas_id,
                'nip' => $request->dosen_id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'jam_mulai' => $request->jam_mulai,
                'jam_berakhir' => $request->jam_berakhir
            ]);
            return back()->with([
                "message" => "Berhasil mengedit data jadwal",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal mengedit data jadwal, Error: " . json_encode($th->getMessage(), true),
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
            foreach (Jadwal::where('id', $request->id)->get() as $deleteItem) {
                $deleteItem->delete();
            }
            Sesi::where('jadwal_id', $request->id)->delete();
            return back()->with([
                "message" => "Berhasil menghapus data jadwal",
                "status" => true,
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal menghapus data jadwal, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }

    public function generatePdf($id)
    {
        $jadwal = Jadwal::find($id);
        $anggotaKelas = AnggotaKelas::with('mahasiswa')->where('kelas_id', $jadwal->kelas_id)->get();
        $presensi = Presensi::where('jadwal_id', $id)->orderBy('sesi_id')->get();
        $data = [];

        foreach ($anggotaKelas as $mahasiswa) {
            $dataMahasiswa = $presensi->where('nim', $mahasiswa->nim);

            $dataPresensi = [];

            foreach ($presensi as $item) {
                if ($item->nim == $mahasiswa->nim) {
                    $dataData = (object) [
                        'pekan' => $item->sesi->sesi,
                        'status' => $this->fixStatus($item->status)
                    ];
                    (object) array_push($dataPresensi, $dataData);
                }
            }

            if (count($dataPresensi) != 18) {
                $countAwal = count($dataPresensi);
                for ($i = $countAwal; $i < 18; $i++) {
                    $dataData = (object) [
                        'pekan' => $i + 1,
                        'status' => "-"
                    ];
                    (object) array_push($dataPresensi, $dataData);
                }
            }


            $addData = (object)[
                'nim' => $mahasiswa->mahasiswa->nim,
                'nama_mahasiswa' => $mahasiswa->mahasiswa->nama_mahasiswa,
                'presensi' => collect($dataPresensi),
                'hadir' => $dataMahasiswa->where('status', "Hadir")->count(),
                'terlambat' => $dataMahasiswa->where('status', "Terlambat")->count(),
                'izin' => $dataMahasiswa->where('status', "Izin")->count(),
                'tidakHadir' => $dataMahasiswa->where('status', "Tidak Hadir")->count()
            ];
            (object) array_push($data, $addData);
        }

        // for ($i = 0; $i < 4; $i++) {
        //     $data = array_merge($data, $data);
        // }

        $data = collect($data);

        // dd($data);
        $pdf = Pdf::loadView('academic/pdf', [
            'data' => $data,
            'jadwal' => $jadwal
        ]);
        $pdf->setPaper('a3', 'landscape');
        return $pdf->stream('Presensi ' . $jadwal->kelas->nama_kelas. ' - '. $jadwal->matkul->nama_matkul.'.pdf');
    }

    function fixStatus($data)
    {
        if ($data == "Hadir") {
            return "H";
        }

        if ($data == "Terlambat") {
            return "T";
        }

        if ($data == "Izin") {
            return "I";
        }

        if ($data == "Tidak Hadir") {
            return "A";
        }
    }
}
