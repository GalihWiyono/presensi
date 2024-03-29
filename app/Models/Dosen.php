<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $primaryKey = 'nip';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'nip',
        'nama_dosen',
        'tanggal_lahir',
        'gender',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    } 

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function log()
    {
        return $this->hasMany(LogDosen::class);
    }
}
