<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    use HasFactory;

    protected $table = 'sesi';

    protected $fillable = [
        'jadwal_id',
        'sesi',
        'tanggal'
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }
}