<?php

namespace App\Helpers;

use App\Models\Budget;
use App\Models\BudgetRelocation;
use App\Models\BudgetRelocationRelation;
use Exception;
use Illuminate\Support\Facades\DB;

class BudgetHelper
{
    public static function create($provinceId, $amount, $userId, $status = 'Masuk', $description = '', $relocation = false)
    {
        if ($relocation == false) {
            Budget::firstOrCreate([
                'province_id' => $provinceId,
                'amount' => $amount,
                'user_id' => $userId,
                'status' => $status,
                'description' => $description,
            ]);
        } else {
            if ($status == 'Masuk') {
                $budget = Budget::firstOrCreate([
                    'province_id' => $provinceId,
                    'amount' => $amount,
                    'user_id' => $userId,
                    'status' => $status,
                    'description' => $description,
                ]);
                return $budget;
            } elseif ($status == 'Keluar') {
                $budget = Budget::firstOrCreate([
                    'province_id' => $provinceId,
                    'amount' => $amount,
                    'user_id' => $userId,
                    'status' => $status,
                    'description' => $description,
                ]);
                return $budget;
            }
        }
    }

    public static function delete($budgetId)
    {
        $budget = Budget::find($budgetId);
        if (!$budget) {
            throw new Exception('Budget not found.');
        }
        $relocation = BudgetRelocation::where('amount_relocation', $budget->amount)
            ->where(function ($query) use ($budget) {
                $query->where('from_province', $budget->province_id)->orWhere('to_province', $budget->province_id);
            })
            ->where('user_id', $budget->user_id)
            ->first();
        if($relocation){
            $relationBudget = BudgetRelocationRelation::with(['budgetRelocation', 'budgetFrom', 'budgetTo'])->where('budget_relocation_id', $relocation->id)->first();
            if($relationBudget){
                $relationBudget->budgetFrom->delete();
                $relationBudget->budgetTo->delete();
                $relationBudget->budgetRelocation->delete();
                $relationBudget->delete();
            }
            $budget->delete();
        }else{
            $currentTotalAmount = BudgetHelper::getTotalBudget($budget->province_id);
            if($currentTotalAmount >= $budget->amount){
                $budget->delete();
            }else{
                throw new Exception('Jumlah total saat ini kurang dari atau sama dengan jumlah anggaran. Operasi penghapusan tidak diizinkan.');
            }
        }
       
    }
    public static function getLastTotalBudget($provinceId, $status = null)
    {
        $budget = Budget::select(DB::raw('SUM(amount) as total_amount'))->where('province_id', $provinceId)->where('status', $status)->first();

        return $budget->total_amount ?? 0;
    }

    public static function getTotalBudget($provinceId)
    {
        $budget = Budget::select(
            DB::raw('
                SUM(CASE WHEN status = "Masuk" THEN amount ELSE 0 END) -
                SUM(CASE WHEN status = "Keluar" THEN amount ELSE 0 END) as total_amount
            '),
        )
            ->where('province_id', $provinceId)
            ->first();
        return $budget->total_amount;
    }
}
