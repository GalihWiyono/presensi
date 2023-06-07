<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogDosen extends Model
{
    use HasFactory;

    protected $table = 'log_dosen';

    protected $fillable = [
        'nip',
        'activity'
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class,'nip', 'nip');
    }

}
