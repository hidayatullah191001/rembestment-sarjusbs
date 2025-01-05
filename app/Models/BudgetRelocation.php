<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetRelocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_province',
        'to_province',
        'amount_relocation',
        'start_amount',
        'final_amount',
        'to_start_amount',
        'to_final_amount',
        'user_id',
        'is_true',
        'description',
    ];

}
