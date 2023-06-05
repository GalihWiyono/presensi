<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\Sesi;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        $filter = "";

        //get ID for filter
        if (request('filter') == "Course") {
            $filter = $matkul->where('nama_matkul', 'like', '%' . request('search') . '%')->first()->id;
        }

        if (request('filter') == "Class") {
            $filter = $kelas->where('nama_kelas', 'like', '%' . request('search') . '%')->first()->id;
        }

        if (request('filter') == "Lecture") {
            $filter = $dosen->where('nama_dosen', 'like', '%' . request('search') . '%')->first()->nip;
        }

        if (request('search')) {
            $jadwal->when(request('filter') == 'Course', function ($q) use ($filter) {
                return $q->where('matkul_id', $filter);
            });
            $jadwal->when(request('filter') == 'Class', function ($q) use ($filter) {
                return $q->where('kelas_id', $filter);
            });
            $jadwal->when(request('filter') == 'Lecture', function ($q) use ($filter) {
                return $q->where('nip', $filter);
            });
            $jadwal->when(request('filter') == 'Course', function ($q) use ($filter) {
                return $q->where('matkul_id', $filter);
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

                for ($i=1; $i<19 ; $i++) { 
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
            'tanggal' => $jadwal->tanggal_mulai->addDays(($sesi-1)*7),
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
            Jadwal::where('id', $request->id)->update([
                "matkul_id" => $request->matkul_id,
                'kelas_id' => $request->kelas_id,
                'nip' => $request->dosen_id,
                'hari' => $request->hari,
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
            if (Jadwal::find($request->id)->delete()) {
                return back()->with([
                    "message" => "Berhasil menghapus data jadwal",
                    "status" => true,
                ]);
            }
        } catch (\Throwable $th) {
            return back()->with([
                "message" => "Gagal menghapus data jadwal, Error: " . json_encode($th->getMessage(), true),
                "status" => false,
            ]);
        }
    }
}
