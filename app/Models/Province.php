<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function budgets(){
        return $this->hasMany(Budget::class);
    }

    public function budgetRelocations()
    {
        return $this->hasMany(BudgetRelocation::class, 'from_province', 'id');
    }
}
