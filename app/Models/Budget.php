<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;
    protected $with = ['user', 'province'];
    protected $fillable = [
        'province_id',
        'user_id',
        'amount',
        'description',
        'status'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function relocationsFrom()
    {
        return $this->hasMany(BudgetRelocationRelation::class, 'budget_from_id');
    }

    // Relasi untuk budget_to_id
    public function relocationsTo()
    {
        return $this->hasMany(BudgetRelocationRelation::class, 'budget_to_id');
    }

}