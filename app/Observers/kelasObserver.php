<?php

namespace App\Observers;

use App\Models\Kelas;
use App\Models\LogAdmin;

class kelasObserver
{
    /**
     * Handle the Kelas "created" event.
     *
     * @param  \App\Models\Kelas  $kelas
     * @return void
     */
    public function created(Kelas $kelas)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Kelas',
            'activity' => "Menambah data Kelas: $kelas->nama_kelas"
        ]);
    }

    /**
     * Handle the Kelas "updated" event.
     *
     * @param  \App\Models\Kelas  $kelas
     * @return void
     */
    public function updated(Kelas $kelas)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Kelas',
            'activity' => "Mengubah data Kelas: $kelas->nama_kelas"
        ]);
    }

    /**
     * Handle the Kelas "deleted" event.
     *
     * @param  \App\Models\Kelas  $kelas
     * @return void
     */
    public function deleted(Kelas $kelas)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Kelas',
            'activity' => "Menghapus data Kelas: $kelas->nama_kelas"
        ]);
    }

    /**
     * Handle the Kelas "restored" event.
     *
     * @param  \App\Models\Kelas  $kelas
     * @return void
     */
    public function restored(Kelas $kelas)
    {
        //
    }

    /**
     * Handle the Kelas "force deleted" event.
     *
     * @param  \App\Models\Kelas  $kelas
     * @return void
     */
    public function forceDeleted(Kelas $kelas)
    {
        //
    }
}
