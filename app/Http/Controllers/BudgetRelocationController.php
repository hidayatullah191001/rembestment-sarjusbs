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
            $fromBudgetDB = Budget::select(
                DB::raw('
                    SUM(CASE WHEN status = "Masuk" THEN amount ELSE 0 END) -
                    SUM(CASE WHEN status = "Keluar" THEN amount ELSE 0 END) as total_amount
                '),
            )
                ->where('province_id', $fromProvinceId)
                ->first();

            if ($fromBudgetDB->total_amount < $request->amount_relocation_value) {
                return redirect()->route('budget.index')->with('error', 'Budget asal tidak cukup');
            } else {
                $toBudget = Budget::where('province_id', $toProvinceId)->first();
                if ($toBudget) {
                    $toBudgetDB = Budget::select(
                        DB::raw('
                            SUM(CASE WHEN status = "Masuk" THEN amount ELSE 0 END) -
                            SUM(CASE WHEN status = "Keluar" THEN amount ELSE 0 END) as total_amount
                        '),
                    )
                        ->where('province_id', $toProvinceId)
                        ->first();

                    // $budgetFrom = Budget::firstOrCreate([
                    //     'province_id' => $fromProvinceId,
                    //     'amount' => $request->amount_relocation_value,
                    //     'user_id' => Auth::user()->id,
                    //     'status' => 'Keluar',
                    //     'description' => $request->description,
                    // ]);
                    $budgetFrom = BudgetHelper::create($fromProvinceId, $request->amount_relocation_value, Auth::user()->id, 'Keluar', $request->description, true);

                    // $budgetTo = Budget::firstOrCreate([
                    //     'province_id' => $toProvinceId,
                    //     'amount' => $request->amount_relocation_value,
                    //     'user_id' => Auth::user()->id,
                    //     'status' => 'Masuk',
                    //     'description' => $request->description,
                    // ]);
                    $budgetTo = BudgetHelper::create($toProvinceId, $request->amount_relocation_value, Auth::user()->id, 'Masuk', $request->description, true);

                    $budgetRelocation = BudgetRelocation::create([
                        'from_province' => $fromProvinceId,
                        'to_province' => $toProvinceId,
                        'amount_relocation' => $request->amount_relocation_value,
                        'start_amount' => $fromBudgetDB->total_amount,
                        'final_amount' => $fromBudgetDB->total_amount + $request->amount_relocation_value, // Amount setelah dikurangi
                        'to_start_amount' => $toBudgetDB->total_amount,
                        'to_final_amount' => $toBudgetDB->total_amount + $request->amount_relocation_value,
                        'user_id' => auth()->id(),
                        'is_true' => false, // Status relokasi
                        'description' => $request->description,
                    ]);
                    BudgetRelocationRelation::create([
                        'budget_relocation_id' => $budgetRelocation->id,
                        'budget_from_id' => $budgetFrom->id,
                        'budget_to_id' => $budgetTo->id,
                    ]);
                } else {
                    return redirect()->route('budget.index')->with('error', 'Maaf, tidak bisa melakukan relokasi budget karena provinsi tujuan belum pernah dibuat budget awal. Solusi : Buat budgetnya di menu create dengan provinsi tersebut');
                }
            }
            DB::commit();
            return redirect()->route('budget.index')->with('success', 'Relokasi budget berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('budget.index')
                ->with('error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
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
