<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Presensi;
use App\Models\Sesi;
use App\Observers\adminObserver;
use App\Observers\dosenObserver;
use App\Observers\jadwalObserver;
use App\Observers\kelasObserver;
use App\Observers\mahasiswaObserver;
use App\Observers\matkulObserver;
use App\Observers\presensiObserver;
use App\Observers\sesiObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Mahasiswa::observe(mahasiswaObserver::class);
        Dosen::observe(dosenObserver::class);
        Kelas::observe(kelasObserver::class);
        MataKuliah::observe(matkulObserver::class);
        Admin::observe(adminObserver::class);
        Jadwal::observe(jadwalObserver::class);
        Presensi::observe(presensiObserver::class);
        Sesi::observe(sesiObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
