<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\LogAdmin;
use App\Models\LogDosen;
use App\Models\LogMahasiswa;
use App\Models\LogSystem;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Presensi;
use App\Models\Sesi;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Nette\Utils\Arrays;

class DashboardController extends Controller
{
    public function index()
    {
        $loggedInUser = auth()->user();
        $dataUser = '';
        $dataDashboard = "";

        if ($loggedInUser->role == "Admin") {
            $dataUser = $loggedInUser->admin;
            $dataDashboard = $this->getAdminData($dataUser);
        }

        if ($loggedInUser->role == "Mahasiswa") {
            $dataUser = $loggedInUser->mahasiswa;
            $dataDashboard = $this->getMahasiswaData($dataUser);
        }

        if ($loggedInUser->role == "Dosen") {
            $dataUser = $loggedInUser->dosen;
            $dataDashboard = $this->getDosenData($dataUser);
        }

        return view('dashboard/dashboard', ['data' => $dataUser, 'dashboard' => $dataDashboard]);
    }

    public function getAdminData($dataUser)
    {
        $mahasiswa = Mahasiswa::count();
        $dosen = Dosen::count();
        $admin = Admin::count();
        $kelas = Kelas::count();
        $matkul = MataKuliah::count();
        $jadwal = Jadwal::count();

        $dataTotal = (object) [
            "mahasiswa" => $mahasiswa,
            "dosen" => $dosen,
            "admin" => $admin,
            "kelas" => $kelas,
            "matkul" => $matkul,
            "jadwal" => $jadwal,
        ];

        //log admin
        $logAdmin = LogAdmin::latest();

        //log system
        $logSystem = LogSystem::latest();

        return (object)[
            'count' => $dataTotal,
            'logAdmin' => $logAdmin->paginate(3, ['*'], 'logAdmin')->withQueryString(),
            'logSystem' => $logSystem->paginate(3, ['*'], 'logSystem')->withQueryString()
        ];
    }

    public function getDosenData($dataUser)
    {
        $jadwal = Jadwal::all();
        $jadwalByNip = $jadwal->where('nip', $dataUser->nip);
        $jadwalObj = (object)[
            'total' => count($jadwal),
            'byNip' => count($jadwalByNip)
        ];

        //get data dashboard kelas
        $kelas = Kelas::all();
        $kelasObj = (object)[
            'total' => count($kelas),
            'byNip' => count($jadwalByNip)
        ];

        //get data dashboard matkul
        $matkul = MataKuliah::all();
        $matkulObj = (object)[
            'total' => count($matkul),
            'byNip' => count($jadwalByNip)
        ];

        //upcoming activities
        $now = Carbon::today();
        $weekStartDate = $now->startOfWeek(CarbonInterface::MONDAY);
        $weekEndDate = $now->copy()->endOfWeek(CarbonInterface::SUNDAY);

        // $now = Carbon::today();
        // $weekStartDate = $now;
        // $weekEndDate = $now->copy()->addDays(7);

        $sesiIds = [];
        foreach ($jadwalByNip as $item) {
            $sesiIds[] = $item->id;
        }

        $activity = Sesi::with('jadwal', 'jadwal.matkul', 'jadwal.kelas')->whereBetween('tanggal', [$weekStartDate, $weekEndDate])->whereIn('jadwal_id', $sesiIds)->where('status', 'Belum')->orderBy('tanggal', 'ASC');

        //log
        $log = LogDosen::where('nip', $dataUser->nip);

        return (object)[
            'jadwal' => $jadwalObj,
            'kelas' => $kelasObj,
            'matkul' => $matkulObj,
            'activity' => $activity->paginate(3, ['*'], 'activity')->withQueryString(),
            'log' => $log->paginate(4, ['*'], 'log')->withQueryString()
        ];
    }

    public function getMahasiswaData($dataUser)
    {
        $jadwal = Jadwal::all();
        $jadwalByKelas = $jadwal->where('kelas_id', $dataUser->kelas_id);
        $jadwalObj = (object)[
            'total' => count($jadwal),
            'byNip' => count($jadwalByKelas)
        ];

        $matkul = MataKuliah::all();
        $matkulObj = (object)[
            'total' => count($matkul),
            'byNip' => count($jadwalByKelas)
        ];

        //upcoming activities
        $now = Carbon::today();
        $weekStartDate = $now->startOfWeek(CarbonInterface::MONDAY);
        $weekEndDate = $now->copy()->endOfWeek(CarbonInterface::SUNDAY);

        // $now = Carbon::today();
        // $weekStartDate = $now;
        // $weekEndDate = $now->copy()->addDays(7);

        $sesiIds = [];
        foreach ($jadwalByKelas as $item) {
            $sesiIds[] = $item->id;
        }

        $activity = Sesi::with('jadwal', 'jadwal.matkul', 'jadwal.kelas')->whereBetween('tanggal', [$weekStartDate, $weekEndDate])->whereIn('jadwal_id', $sesiIds)->where('status', 'Belum')->orderBy('tanggal', 'ASC');

        //log
        $log = LogMahasiswa::where('nim', $dataUser->nim);

        return (object)[
            'jadwal' => $jadwalObj,
            'matkul' => $matkulObj,
            'akun' => $dataUser,
            'activity' => $activity->paginate(3, ['*'], 'activity')->withQueryString(),
            'log' => $log->paginate(4, ['*'], 'log')->withQueryString()
        ];
    }
}
