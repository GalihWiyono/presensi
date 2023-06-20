<?php

namespace App\Observers;

use App\Models\LogAdmin;
use App\Models\MataKuliah;

class matkulObserver
{
    /**
     * Handle the MataKuliah "created" event.
     *
     * @param  \App\Models\MataKuliah  $mataKuliah
     * @return void
     */
    public function created(MataKuliah $mataKuliah)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Mata Kuliah',
            'activity' => "Menambah data Mata Kuliah: $mataKuliah->nama_matkul"
        ]);
    }

    /**
     * Handle the MataKuliah "updated" event.
     *
     * @param  \App\Models\MataKuliah  $mataKuliah
     * @return void
     */
    public function updated(MataKuliah $mataKuliah)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Mata Kuliah',
            'activity' => "Mengubah data Mata Kuliah: $mataKuliah->nama_matkul"
        ]);
    }

    /**
     * Handle the MataKuliah "deleted" event.
     *
     * @param  \App\Models\MataKuliah  $mataKuliah
     * @return void
     */
    public function deleted(MataKuliah $mataKuliah)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Mata Kuliah',
            'activity' => "Menghapus data Mata Kuliah: $mataKuliah->nama_matkul"
        ]);
    }

    /**
     * Handle the MataKuliah "restored" event.
     *
     * @param  \App\Models\MataKuliah  $mataKuliah
     * @return void
     */
    public function restored(MataKuliah $mataKuliah)
    {
        //
    }

    /**
     * Handle the MataKuliah "force deleted" event.
     *
     * @param  \App\Models\MataKuliah  $mataKuliah
     * @return void
     */
    public function forceDeleted(MataKuliah $mataKuliah)
    {
        //
    }
}
