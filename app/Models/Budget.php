<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'province_id',
        'user_id',
        'amount',
        'description',
        'total_amount',
        'status'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function province(){
        return $this->belongsTo(Province::class);
    }
}