<?php

namespace App\Observers;

use App\Models\LogDosen;
use App\Models\Sesi;

class sesiObserver
{
    /**
     * Handle the Sesi "created" event.
     *
     * @param  \App\Models\Sesi  $sesi
     * @return void
     */
    public function created(Sesi $sesi)
    {
        //
    }

    /**
     * Handle the Sesi "updated" event.
     *
     * @param  \App\Models\Sesi  $sesi
     * @return void
     */
    public function updated(Sesi $sesi)
    {
        $loggedIn = auth()->user()->dosen;
        LogDosen::create([
            'nip' =>$loggedIn->nip,
            'kelas_id' => $sesi->jadwal->kelas_id,
            'affected' => 'Kelas',
            'activity' => "Menutup Pekan $sesi->sesi pada Mata Kuliah " . $sesi->jadwal->matkul->nama_matkul . " Kelas " . $sesi->jadwal->kelas->nama_kelas . ": Manual"
        ]);
    }

    /**
     * Handle the Sesi "deleted" event.
     *
     * @param  \App\Models\Sesi  $sesi
     * @return void
     */
    public function deleted(Sesi $sesi)
    {
        //
    }

    /**
     * Handle the Sesi "restored" event.
     *
     * @param  \App\Models\Sesi  $sesi
     * @return void
     */
    public function restored(Sesi $sesi)
    {
        //
    }

    /**
     * Handle the Sesi "force deleted" event.
     *
     * @param  \App\Models\Sesi  $sesi
     * @return void
     */
    public function forceDeleted(Sesi $sesi)
    {
        //
    }
}
