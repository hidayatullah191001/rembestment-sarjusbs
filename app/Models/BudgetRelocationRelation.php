<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetRelocationRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_relocation_id',
        'budget_from_id', 
        'budget_to_id'
    ];
}
