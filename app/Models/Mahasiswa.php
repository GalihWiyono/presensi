<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $primaryKey = 'nim';

    protected $keyType = 'string';

    protected $fillable = [
        'nama_mahasiswa',
        'tanggal_lahir',
        'gender',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'nim', 'nim');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class,);
    }

}
