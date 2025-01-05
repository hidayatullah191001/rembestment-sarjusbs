<?php

namespace App\Http\Controllers;

use App\Helpers\BudgetHelper;
use App\Helpers\MyHelper;
use App\Models\Budget;
use App\Models\BudgetRelocation;
use App\Models\BudgetRelocationRelation;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $budgets = Budget::select('budgets.*')->join(DB::raw('(SELECT MAX(id) as latest_id FROM budgets GROUP BY province_id) as latest_budget'), 'budgets.id', '=', 'latest_budget.latest_id')->get();
        $provinces = Province::all();
        return view('pages.budget.index', compact('budgets', 'provinces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::all();
        return view('pages.budget.form', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'province_id' => 'required',
            'amount_value' => 'required|numeric|',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors();
            return redirect()->route('budget.create')->withErrors($errors)->withInput();
        }

        DB::beginTransaction();

        try {
            BudgetHelper::create($request->province_id, $request->amount_value, Auth::user()->id, 'Masuk', $request->description, false);

            DB::commit();

            return redirect()->route('budget.index')->with('success', 'Data budget successfully created');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('budget.index')
                ->with('error', 'Something went wrong :' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $encodeId)
    {
        $provinceId = MyHelper::decodeID($encodeId);
        $province = Province::with(['budgets', 'budgetRelocations'])
            ->where('id', $provinceId)
            ->first();
        $lastTotalBudgetMasuk = BudgetHelper::getLastTotalBudget($provinceId, 'Masuk');
        $lastTotalBudgetKeluar = BudgetHelper::getLastTotalBudget($provinceId, 'Keluar');
        return view('pages.budget.show', [
            'province' => $province,
            'lastTotalBudgetMasuk' => $lastTotalBudgetMasuk,
            'lastTotalBudgetKeluar' => $lastTotalBudgetKeluar,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Budget $budget)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $encodeId)
    {
        $budgetId = MyHelper::decodeID($encodeId);
        DB::beginTransaction();
        try {
            BudgetHelper::delete($budgetId);
            DB::commit();
            return redirect()->route('budget.index')->with('success', 'Data budget successfully deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('budget.index')
                ->with('error', 'Something went wrong :' . $e->getMessage());
        }
    }

    public function getRelocations($id)
    {
        $budget = Budget::findOrFail($id);
        $relocation = BudgetRelocation::where('amount_relocation', $budget->amount)
        ->where(function ($query) use ($budget) {
            $query->where('from_province', $budget->province_id)->orWhere('to_province', $budget->province_id);
        })
        ->where('user_id', $budget->user_id)
        ->first();
        $relationBudget = BudgetRelocationRelation::with(['budgetRelocation', 'budgetFrom', 'budgetTo'])->where('budget_relocation_id', $relocation->id)->first();
        
        $relocationInfo = null;

        if ($budget->status == 'Keluar') {
            $relocationInfo = $relationBudget->budgetTo;
        } elseif ($budget->status == 'Masuk') {
            $relocationInfo = $relationBudget->budgetFrom;
        }
        return response()->json([
            'success' => true,
            'budget' => $budget,
            'relocation_info' => $relocationInfo,
        ]);
    }
}
