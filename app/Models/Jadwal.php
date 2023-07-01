<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'matkul_id',
        'kelas_id',
        'nip',
        'tanggal_mulai',
        'jam_mulai',
        'jam_berakhir',
        'mulai_absen',
        'akhir_absen'
    ];

    protected $dates = ['created_at', 'updated_at', 'tanggal_mulai'];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }

    public function matkul()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function sesi()
    {
        return $this->hasMany(Sesi::class);
    } 

    public function qrcode()
    {
        return $this->hasMany(Qrcode::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }
}
