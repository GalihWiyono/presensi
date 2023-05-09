<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admins';

    protected $primaryKey = 'nip';

    protected $keyType = 'string';

    protected $fillable = [
        'nip',
        'nama_admin',
        'tanggal_lahir',
        'gender',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    } 
}
