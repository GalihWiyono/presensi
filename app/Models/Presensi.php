<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';
    
    protected $fillable = [
        'jadwal_id',
        'nim',
        'waktu_presensi',
        'status'
    ];

    public function sesi()
    {
        return $this->belongsTo(Sesi::class);
    }

    public function getPresensiOnSesi($sesi)
    {
        return $this->belongsTo(Sesi::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

}
