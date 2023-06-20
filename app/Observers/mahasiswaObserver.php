<?php

namespace App\Observers;

use App\Models\LogAdmin;
use App\Models\Mahasiswa;

class mahasiswaObserver
{
    /**
     * Handle the Mahasiswa "created" event.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return void
     */
    public function created(Mahasiswa $mahasiswa)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Mahasiswa',
            'activity' => "Menambah data Mahasiswa: NIM$mahasiswa->nim"
        ]);
    }

    /**
     * Handle the Mahasiswa "updated" event.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return void
     */
    public function updated(Mahasiswa $mahasiswa)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Mahasiswa',
            'activity' => "Mengubah data Mahasiswa: NIM $mahasiswa->nim"
        ]);
    }

    /**
     * Handle the Mahasiswa "deleted" event.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return void
     */
    public function deleted(Mahasiswa $mahasiswa)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Mahasiswa',
            'activity' => "Menghapus data Mahasiswa: NIM $mahasiswa->nim"
        ]);
    }

    /**
     * Handle the Mahasiswa "restored" event.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return void
     */
    public function restored(Mahasiswa $mahasiswa)
    {

    }

    /**
     * Handle the Mahasiswa "force deleted" event.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return void
     */
    public function forceDeleted(Mahasiswa $mahasiswa)
    {
        //
    }
}
