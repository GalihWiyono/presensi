<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pending extends Model
{
    use HasFactory;

    protected $table = 'pending';

    protected $fillable = [
        'nip',
        'jadwal_id',
        'sesi_id',
        'tanggal',
        'tanggal_baru',
        'mulai_absen_baru',
        'akhir_absen_baru',
        'status'
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    public function sesi()
    {
        return $this->belongsTo(Sesi::class);
    }
}
