<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qrcode extends Model
{
    use HasFactory;

    protected $table = 'qrcode';

    protected $fillable = [
        'unique',
        'jadwal_id',
        'sesi_id',
        'tanggal',
        'mulai_absen',
        'akhir_absen',
        'status'
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function sesi()
    {
        return $this->belongsTo(Sesi::class);
    }

}
