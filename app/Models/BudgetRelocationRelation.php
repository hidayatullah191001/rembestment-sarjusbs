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

    public function budgetRelocation()
    {
        return $this->belongsTo(BudgetRelocation::class);
    }

    public function budgetFrom()
    {
        return $this->belongsTo(Budget::class, 'budget_from_id');
    }

    public function budgetTo()
    {
        return $this->belongsTo(Budget::class, 'budget_to_id');
    }
}
