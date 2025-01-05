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
        'pdf_path'
    ];

    public function peserta()
    {
        return $this->hasMany(UserEntertainPeserta::class, 'id_user_entertain');
    }

}
