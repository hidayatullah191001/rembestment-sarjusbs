<?php

namespace App\Http\Controllers;

use App\Helpers\BudgetHelper;
use App\Models\Budget;
use App\Models\BudgetRelocation;
use App\Models\BudgetRelocationRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BudgetRelocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'from_province_value' => 'required|exists:provinces,id',
            'to_province' => 'required|exists:provinces,id',
            'description' => 'nullable|string|max:255',
            'amount_relocation_value' => 'required|numeric|min:1',
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors();
            return redirect()
                ->route('budget.index')
                ->with('error', 'Erorr :' . $errors);
        }

        DB::beginTransaction();

        $fromProvinceId = $request->from_province_value;
        $toProvinceId = $request->to_province;

        try {
            $fromBudget = Budget::select('budgets.*')->join(DB::raw('(SELECT MAX(id) as latest_id FROM budgets GROUP BY province_id) as latest_budget'), 'budgets.id', '=', 'latest_budget.latest_id')->where('budgets.province_id', $fromProvinceId)->first();
            $toBudget = Budget::select('budgets.*')->join(DB::raw('(SELECT MAX(id) as latest_id FROM budgets GROUP BY province_id) as latest_budget'), 'budgets.id', '=', 'latest_budget.latest_id')->where('budgets.province_id', $toProvinceId)->first();
            if ($fromBudget->total_amount < $request->amount_relocation_value) {
                return redirect()->route('budget.index')->with('error', 'Budget asal tidak cukup');
            }
            $currentTotal = $fromBudget->total_amount -= $request->amount_relocation_value;
            $resultFromBudget = BudgetHelper::create($fromProvinceId, $request->amount_relocation_value, Auth::user()->id, 'Keluar', $request->description, true, $currentTotal);
            if ($toBudget) {
                $currentTotal = $toBudget->total_amount += $request->amount_relocation_value;
                $resultToBudget = BudgetHelper::create($toProvinceId, $request->amount_relocation_value, Auth::user()->id, 'Masuk', $request->description, true, $currentTotal);
                $relocation = BudgetRelocation::create([
                    'from_province' => $fromProvinceId,
                    'to_province' => $toProvinceId,
                    'amount_relocation' => $request->amount_relocation_value,
                    'start_amount' => $fromBudget->total_amount + $request->amount_relocation_value, // Amount sebelum dikurangi
                    'final_amount' => $fromBudget->total_amount, // Amount setelah dikurangi
                    'to_start_amount' => $toBudget->total_amount - $request->amount_relocation_value,
                    'to_final_amount' => $toBudget->total_amount,
                    'user_id' => auth()->id(),
                    'is_true' => false, // Status relokasi
                    'description' => $request->description,
                ]);

                BudgetRelocationRelation::create([
                    'budget_relocation_id' => $relocation->id,
                    'budget_from_id' => $resultFromBudget->id,
                    'budget_to_id' => $resultToBudget->id,
                ]);
            } else {
                return redirect()->route('budget.index')->with('error', 'Maaf, tidak bisa melakukan relokasi budget karena provinsi tujuan belum pernah dibuat budget awal. Solusi : Buat budgetnya di menu create dengan provinsi tersebut');
            }
            DB::commit();
            return redirect()->route('budget.index')->with('success', 'Relokasi budget berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('budget.index')->with('error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BudgetRelocation $budgetRelocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BudgetRelocation $budgetRelocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BudgetRelocation $budgetRelocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BudgetRelocation $budgetRelocation)
    {
        //
    }
}
