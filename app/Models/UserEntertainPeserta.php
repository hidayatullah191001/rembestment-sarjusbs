<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEntertainPeserta extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_entertain_id',
        'nama_pelanggan',
        'internal_icon'
    ];
    public function userEntertain()
    {
        return $this->belongsTo(UserEntertain::class, 'id_user_entertain');
    }
}
