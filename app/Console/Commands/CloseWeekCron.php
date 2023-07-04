<?php

namespace App\Console\Commands;

use App\Models\Jadwal;
use App\Models\LogSystem;
use App\Models\Presensi;
use App\Models\Sesi;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CloseWeekCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'close:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Close Week';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $time = Carbon::now()->format('d-m-Y H:i:s');
        $dateToday = Carbon::now()->toDateString();

        try {
            $jadwal = Jadwal::all();
            $dataJadwal = [];

            foreach ($jadwal as $item) {
                $dataJadwal[] = $item->id;
            }

            $sesi = Sesi::whereIn('jadwal_id', $dataJadwal)->where([
                'tanggal' => $dateToday,
                'status' => "Belum"
            ])->with('jadwal')->get();

            foreach ($sesi as $sesiItem) {
                $jadwalData = $sesiItem->jadwal;
                $anggotaKelas = $jadwalData->kelas->anggota_kelas;

                foreach ($anggotaKelas as $mahasiswa) {
                    $absen = Presensi::firstOrCreate([
                        'nim' => $mahasiswa->nim,
                        'sesi_id' => $sesiItem->id
                    ], [
                        'sesi_id' => $sesiItem->id,
                        'jadwal_id' => $sesiItem->jadwal_id,
                        'nim' => $mahasiswa->nim,
                        'status' => "Tidak Hadir"
                    ]);
                }

                $sesiItem->update(['status' => 'Selesai']);
            };

            LogSystem::create([
                'activity' => "Auto Close Week Success on $time",
                'status' => "Success"
            ]);
            return Command::SUCCESS;
        } catch (\Throwable $th) {
            LogSystem::create([
                'activity' => "Auto Close Week Failed, Error:" . json_encode($th->getMessage(), true),
                'status' => "Failed"
            ]);
            return Command::FAILURE;
        }
    }
}
