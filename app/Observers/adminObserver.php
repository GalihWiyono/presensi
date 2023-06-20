<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\LogAdmin;

class adminObserver
{
    /**
     * Handle the Admin "created" event.
     *
     * @param  \App\Models\Admin  $admin
     * @return void
     */
    public function created(Admin $admin)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Admin',
            'activity' => "Menambah data Admin: $admin->nama_admin"
        ]);
    }

    /**
     * Handle the Admin "updated" event.
     *
     * @param  \App\Models\Admin  $admin
     * @return void
     */
    public function updated(Admin $admin)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Admin',
            'activity' => "Mengubah data Admin: $admin->nama_admin"
        ]);
    }

    /**
     * Handle the Admin "deleted" event.
     *
     * @param  \App\Models\Admin  $admin
     * @return void
     */
    public function deleted(Admin $admin)
    {
        $loggedIn = auth()->user()->admin;
        LogAdmin::create([
            'nip' => $loggedIn->nip,
            'affected' => 'Admin',
            'activity' => "Menghapus data Admin: $admin->nama_admin"
        ]);
    }

    /**
     * Handle the Admin "restored" event.
     *
     * @param  \App\Models\Admin  $admin
     * @return void
     */
    public function restored(Admin $admin)
    {
        //
    }

    /**
     * Handle the Admin "force deleted" event.
     *
     * @param  \App\Models\Admin  $admin
     * @return void
     */
    public function forceDeleted(Admin $admin)
    {
        //
    }
}
