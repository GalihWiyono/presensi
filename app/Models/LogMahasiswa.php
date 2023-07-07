<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'log_mahasiswa';

    protected $fillable = [
        'nim',
        'jadwal_id',
        'activity'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class,'nim', 'nim');
    }
}
