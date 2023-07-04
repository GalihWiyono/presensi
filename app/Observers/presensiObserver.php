<?php

namespace App\Observers;

use App\Models\LogAdmin;
use App\Models\LogDosen;
use App\Models\LogMahasiswa;
use App\Models\Presensi;

class presensiObserver
{
    /**
     * Handle the Presensi "created" event.
     *
     * @param  \App\Models\Presensi  $presensi
     * @return void
     */
    public function created(Presensi $presensi)
    {
        $loggedIn = auth()->user();
        // if ($loggedIn->role == "Admin") {
        //     LogAdmin::create([
        //         'nip' => $loggedIn->admin->nip,
        //         'affected' => 'Presensi',
        //         'activity' => "Menambah data Presensi: $presensi->nim pada Jadwal " . $presensi->sesi->jadwal->matkul->nama_matkul . " Status : $presensi->status"   
        //     ]);
        // }

        // if ($loggedIn->role == "Dosen") {
        //     LogDosen::create([
        //         'nip' =>$loggedIn->dosen->nip,
        //         'nim' =>$presensi->nim,
        //         'kelas_id' => $presensi->sesi->jadwal->kelas_id,
        //         'activity' => "Menambah Presensi: Kelas " . $presensi->sesi->jadwal->kelas->nama_kelas . " NIM $presensi->nim Pekan " . $presensi->sesi->sesi . " Status $presensi->status"
        //     ]);
        // }

        if ($loggedIn->role != null && $loggedIn->role == "Mahasiswa") {
            LogMahasiswa::create([
                'nim' => $presensi->nim,
                'jadwal_id' => $presensi->sesi->jadwal->id,
                'activity' => "Presensi Pekan " . $presensi->sesi->sesi . " " . $presensi->sesi->jadwal->matkul->nama_matkul . " : $presensi->status"
            ]);
        }
    }

    /**
     * Handle the Presensi "updated" event.
     *
     * @param  \App\Models\Presensi  $presensi
     * @return void
     */
    public function updated(Presensi $presensi)
    {
        $loggedIn = auth()->user();
        if ($loggedIn->role != null) {
            if ($loggedIn->role == "Admin") {
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
            }

            if ($loggedIn->role == "Dosen") {
                LogDosen::create([
                    'nip' => $loggedIn->dosen->nip,
                    'nim' => $presensi->nim,
                    'kelas_id' => $presensi->sesi->jadwal->kelas_id,
                    'affected' => 'Mahasiswa',
                    'activity' => "Mengubah Presensi: Kelas " . $presensi->sesi->jadwal->kelas->nama_kelas . " NIM $presensi->nim Pekan " . $presensi->sesi->sesi . " Status $presensi->status"
                ]);
            }
        }
    }

    /**
     * Handle the Presensi "deleted" event.
     *
     * @param  \App\Models\Presensi  $presensi
     * @return void
     */
    public function deleted(Presensi $presensi)
    {
        //
    }

    /**
     * Handle the Presensi "restored" event.
     *
     * @param  \App\Models\Presensi  $presensi
     * @return void
     */
    public function restored(Presensi $presensi)
    {
        //
    }

    /**
     * Handle the Presensi "force deleted" event.
     *
     * @param  \App\Models\Presensi  $presensi
     * @return void
     */
    public function forceDeleted(Presensi $presensi)
    {
        //
    }
}
