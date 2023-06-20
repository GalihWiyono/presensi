<?php

namespace App\Observers;

use App\Models\Dosen;
use App\Models\LogAdmin;

class dosenObserver
{
    /**
     * Handle the Dosen "created" event.
     *
     * @param  \App\Models\Dosen  $dosen
     * @return void
     */
    public function created(Dosen $dosen)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Dosen',
            'activity' => "Menambah data Dosen: NIP $dosen->nip"
        ]);
    }

    /**
     * Handle the Dosen "updated" event.
     *
     * @param  \App\Models\Dosen  $dosen
     * @return void
     */
    public function updated(Dosen $dosen)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Dosen',
            'activity' => "Mengubah data Dosen: NIP $dosen->nip"
        ]);
    }

    /**
     * Handle the Dosen "deleted" event.
     *
     * @param  \App\Models\Dosen  $dosen
     * @return void
     */
    public function deleted(Dosen $dosen)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Dosen',
            'activity' => "Menghapus data Dosen: NIP $dosen->nip"
        ]);
    }

    /**
     * Handle the Dosen "restored" event.
     *
     * @param  \App\Models\Dosen  $dosen
     * @return void
     */
    public function restored(Dosen $dosen)
    {
        //
    }

    /**
     * Handle the Dosen "force deleted" event.
     *
     * @param  \App\Models\Dosen  $dosen
     * @return void
     */
    public function forceDeleted(Dosen $dosen)
    {
        //
    }
}
