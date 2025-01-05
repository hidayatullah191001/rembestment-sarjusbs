<?php

namespace App\Helpers;

use App\Models\Budget;
use App\Models\BudgetRelocation;
use App\Models\BudgetRelocationRelation;
use Exception;
use Illuminate\Support\Facades\DB;

class BudgetHelper
{
    public static function create($provinceId, $amount, $userId, $status = 'Masuk', $description = '', $relocation = false, $currentTotal = null)
    {
        if ($relocation == false) {
            $budget = Budget::select('total_amount')->join(DB::raw('(SELECT MAX(id) as latest_id FROM budgets GROUP BY province_id) as latest_budget'), 'budgets.id', '=', 'latest_budget.latest_id')->where('budgets.province_id', $provinceId)->first();
            if ($budget == null) {
                $currentTotalAmount = $amount;
            } else {
                $currentTotalAmount = $amount + $budget->total_amount;
            }

            Budget::firstOrCreate([
                'province_id' => $provinceId,
                'amount' => $amount,
                'user_id' => $userId,
                'status' => $status,
                'description' => $description,
                'total_amount' => $currentTotalAmount,
            ]);
        } else {
            if ($status == 'Masuk') {
                $budget = Budget::firstOrCreate([
                    'province_id' => $provinceId,
                    'amount' => $amount,
                    'user_id' => $userId,
                    'status' => $status,
                    'description' => $description,
                    'total_amount' => $currentTotal,
                ]);
                return $budget;
            } elseif ($status == 'Keluar') {
                $budget = Budget::firstOrCreate([
                    'province_id' => $provinceId,
                    'amount' => $amount,
                    'user_id' => $userId,
                    'status' => $status,
                    'description' => $description,
                    'total_amount' => $currentTotal,
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

        if ($relocation) {
            $relationBudget = BudgetRelocationRelation::where('budget_relocation_id', $relocation->id)->first();
            // dd($relationBudget);

            if ($relocation->from_province == $budget->province_id && $relocation->id == $relationBudget->budget_relocation_id && $budget->status == 'Keluar') {
                // Ini Kondisi untuk data from
                // 1. Cek dahulu apakah data seterustnya lebih dari 1, jika lebih dari 1 sebelum delete maka update dahulu total_amount selanjutnya
                // dd('kondisi 1');
                $newFromBudgets = Budget::where('province_id', $relocation->from_province)
                    ->where('id', '>', $budget->id)
                    ->get();
                if (count($newFromBudgets) >= 1) {
                    foreach ($newFromBudgets as $item) {
                        $item->total_amount += $budget->amount;
                        $item->save();
                    }
                    $toProvinceBudget = Budget::where('province_id', $relocation->to_province)
                        ->orderBy('id', 'desc')
                        ->first();
                    if ($toProvinceBudget) {
                        $toProvinceBudget->total_amount -= $budget->amount;
                        $toProvinceBudget->save();
                    }
                }
                $toBudget = Budget::find($relationBudget->budget_to_id);
                $toBudget->delete();

            } else {

                $newToBudgets = Budget::where('province_id', $relocation->to_province)
                    ->where('id', '>', $budget->id)
                    ->get();
                if (count($newToBudgets) >= 1) {
                    foreach ($newToBudgets as $item) {
                        $item->total_amount -= $budget->amount;
                        $item->save();
                    }

                    $fromProvinceBudget = Budget::where('province_id', $relocation->from_province)
                        ->orderBy('id', 'desc')
                        ->first();
                    if ($fromProvinceBudget) {
                        $fromProvinceBudget->total_amount += $budget->amount;
                        $fromProvinceBudget->save();
                    }
                }
                $fromBudget = Budget::find($relationBudget->budget_from_id);
                    $fromBudget->delete();
            }

            // if ($relocation->from_province == $budget->province_id && $budget->status == 'Keluar') {

            //     // Tambahkan amount ke total_amount di from_province
            //     $fromProvinceBudget = Budget::where('province_id', $relocation->from_province)
            //         ->orderBy('id', 'desc')
            //         ->first();
            //     if ($fromProvinceBudget) {
            //         $fromProvinceBudget->total_amount += $budget->amount;
            //         $fromProvinceBudget->save();
            //     }
            // } elseif ($relocation->to_province == $budget->province_id && $budget->status == 'Masuk') {
            //     // Kurangi amount dari total_amount di to_province
            //     $toProvinceBudget = Budget::where('province_id', $relocation->to_province)
            //         ->orderBy('id', 'desc')
            //         ->first();
            //     // dd($toProvinceBudget);
            //     if ($toProvinceBudget) {
            //         $toProvinceBudget->total_amount -= $budget->amount;
            //         $toProvinceBudget->save();
            //     }
            // }

            // $relationBudget = BudgetRelocationRelation::where('budget_relocation_id', $relocation->id)->first();

            // if($budget->id == $relationBudget->budget_to_id){
            //     $newBudget = Budget::where('province_id', $budget->province_id)->where('id', '>', $budget->id)->get();

            //     foreach ($newBudget as $item) {
            //         dd($item->total_amount);
            //         $item->total_amount -= ($budget->amount + $item->amount);
            //         $item->update();
            //     }
            //     $fromBudget = Budget::find($relationBudget->budget_from_id);
            //     $fromBudget->delete();
            // }else{
            //     $newBudget = Budget::where('province_id', $budget->province_id)->where('id', '>', $budget->id)->get();

            //     foreach ($newBudget as $item) {

            //         $item->total_amount += ($budget->amount);

            //         $item->update();
            //     }
            //     dd($newBudget);
            //     $toBudget = Budget::find($relationBudget->budget_to_id);

            //     $toBudget->delete();
            // }
            $relocation->delete();
            $relationBudget->delete();
        } else {
            // Ini kondisi jika bukan relocation dan delete data ditengah range, maka harus update semua total_amountnya dikurang dengan amount dari $budget
           
            $newBudget = Budget::where('province_id', $budget->province_id)
                ->where('id', '>=', $budget->id)
                ->get();

            foreach ($newBudget as $item) {
                if($item->total_amount < $budget->amount){
                    $item->total_amount = $item->amount;    
                }else{
                    $item->total_amount -= $budget->amount;
                }
                
                $item->update();
            }
            // dd($newBudget);

        }

        // Hapus data budget
        $budget->delete();
    }

    public static function getLastTotalBudget($provinceId, $status = null)
    {
        if ($status != null) {
            $budget = Budget::select(DB::raw('SUM(amount) as total_amount'))->where('province_id', $provinceId)->where('status', $status)->first();
        } else {
            $budget = Budget::select('total_amount')
                ->where('province_id', $provinceId)
                ->orderBy('id', 'DESC') // Urutkan berdasarkan ID terbaru
                ->first();
        }
        return $budget->total_amount ?? 0;
    }
}
