<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAdmin extends Model
{
    use HasFactory;

    protected $table = 'log_admin';

    protected $fillable = [
        'nip',
        'affected',
        'activity'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class,'nip', 'nip');
    }
}
