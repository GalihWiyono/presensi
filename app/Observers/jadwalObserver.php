<?php

namespace App\Observers;

use App\Models\Jadwal;
use App\Models\LogAdmin;
use App\Models\LogDosen;

class jadwalObserver
{
    /**
     * Handle the Jadwal "created" event.
     *
     * @param  \App\Models\Jadwal  $jadwal
     * @return void
     */
    public function created(Jadwal $jadwal)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Jadwal',
            'activity' => "Menambah data Jadwal: Mata Kuliah " . $jadwal->matkul->nama_matkul . " pada Kelas " . $jadwal->kelas->nama_kelas
        ]);
    }

    /**
     * Handle the Jadwal "updated" event.
     *
     * @param  \App\Models\Jadwal  $jadwal
     * @return void
     */
    public function updated(Jadwal $jadwal)
    {

        $loggedIn = auth()->user();
        if ($loggedIn->role == "Admin") {
            LogAdmin::create([
                'nip' => $loggedIn->admin->nip,
                'affected' => 'Jadwal',
                'activity' => "Mengubah data Jadwal: Mata Kuliah " . $jadwal->matkul->nama_matkul . " pada Kelas " . $jadwal->kelas->nama_kelas
            ]);
        }

        if ($loggedIn->role == "Dosen") {
            LogDosen::create([
                'nip' =>$loggedIn->dosen->nip,
                'kelas_id' => $jadwal->kelas_id,
                'affected' => 'Kelas',
                'activity' => "Mengubah waktu presensi Kelas " . $jadwal->kelas->nama_kelas . " Mata Kuliah " . $jadwal->matkul->nama_matkul . ": $jadwal->mulai_absen - $jadwal->akhir_absen"
            ]);
        }
    }

    /**
     * Handle the Jadwal "deleted" event.
     *
     * @param  \App\Models\Jadwal  $jadwal
     * @return void
     */
    public function deleted(Jadwal $jadwal)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Jadwal',
            'activity' => "Menghapus data Jadwal: Mata Kuliah " . $jadwal->matkul->nama_matkul . " pada Kelas " . $jadwal->kelas->nama_kelas
        ]);
    }

    /**
     * Handle the Jadwal "restored" event.
     *
     * @param  \App\Models\Jadwal  $jadwal
     * @return void
     */
    public function restored(Jadwal $jadwal)
    {
        //
    }

    /**
     * Handle the Jadwal "force deleted" event.
     *
     * @param  \App\Models\Jadwal  $jadwal
     * @return void
     */
    public function forceDeleted(Jadwal $jadwal)
    {
        //
    }
}
