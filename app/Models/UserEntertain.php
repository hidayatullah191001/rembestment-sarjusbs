<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEntertain extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_account_manager',
        'nama_kanwil_manager',
        'hari',
        'tanggal',
        'waktu',
        'type_id',
        'nilai_entertain',
        'revenue',
        'pelanggan',
        'topik',
        'aktivitas',
        'target_pelaksanaan',
    ];

    public function peserta()
    {
        return $this->hasMany(UserEntertainPeserta::class, 'user_entertain_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
}
