<?php

namespace App\Http\Controllers;

use App\Helpers\BudgetHelper;
use App\Models\Budget;
use App\Models\Province;
use App\Models\User;
use App\Models\UserEntertain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'isadmin']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalBudget = BudgetHelper::getTotalBudget() ?? 0;
        $totalProvince = Province::count() ?? 0;
        $userEntertainInfo = UserEntertain::select(DB::raw('SUM(nilai_entertain) as total_entertain'), DB::raw('SUM(revenue) as total_revenue'))->first(); // Menjalankan query untuk mendapatkan hasil

        // Mengakses hasil total
        $totalRembestment = $userEntertainInfo->total_entertain ?? 0; // Default ke 0 jika null
        // $totalRevenue = $userEntertainInfo->total_revenue ?? 0; // Default ke 0 jika null
        $totalUserGuest = User::where('role_id', 2)->count();

        // dd($totalBudget);
        return view('pages.dashboard', [
            'totalBudget' => $totalBudget,
            'totalProvince' => $totalProvince,
            'totalRembestment' => $totalRembestment,
            'totalUserGuest' => $totalUserGuest,
        ]);
    }

    public function getEntertainData(Request $request)
    {
        $year = $request->input('year', now()->year);

        $data = UserEntertain::select(DB::raw('MONTH(user_entertains.created_at) as month'), 'types.name as type_name', DB::raw('SUM(nilai_entertain) as total'))->join('types', 'user_entertains.type_id', '=', 'types.id')->whereYear('user_entertains.created_at', $year)->groupBy('month', 'type_name')->get();

        // Format data untuk Chart.js
        $formattedData = $data->groupBy('type_name')->map(function ($group) {
            return $group->mapWithKeys(function ($item) {
                return [$item->month => $item->total];
            });
        });
        return response()->json([
            'data' => $formattedData,
            'months' => range(1, 12),
        ]);
    }

    public function getEntertainUserInputData(Request $request)
    {
        $year = $request->input('year', now()->year); // Default tahun sekarang

        $data = UserEntertain::select(DB::raw('MONTH(user_entertains.created_at) as month'), 'provinces.name as province_name', DB::raw('COUNT(DISTINCT user_entertains.user_id) as total_users'))->join('users', 'user_entertains.user_id', '=', 'users.id')->join('provinces', 'users.province_id', '=', 'provinces.id')->whereYear('user_entertains.created_at', $year)->groupBy('month', 'province_name')->get();

        // Format data untuk Chart.js
        $formattedData = $data->groupBy('province_name')->map(function ($group) {
            return $group->mapWithKeys(function ($item) {
                return [$item->month => $item->total_users];
            });
        });

        return response()->json([
            'data' => $formattedData,
            'months' => range(1, 12),
        ]);
    }

    public function getBudgetData(Request $request)
    {
        $year = $request->input('year', now()->year); // Default tahun sekarang
        // $status = $request->input('status', 'Masuk'); // Default status "Masuk"

        // $data = Budget::select('provinces.name as province_name', DB::raw('SUM(CASE WHEN status = "Masuk" THEN amount ELSE 0 END) as total_masuk'), DB::raw('SUM(CASE WHEN status = "Keluar" THEN amount ELSE 0 END) as total_keluar'))->join('provinces', 'budgets.province_id', '=', 'provinces.id')->whereYear('budgets.created_at', $year)->where('budgets.status', $status)->groupBy('province_name')->get();

        // // Format data untuk Chart.js
        // $provinces = $data->pluck('province_name');
        // $masukData = $data->pluck('total_masuk');
        // $keluarData = $data->pluck('total_keluar');

        // return response()->json([
        //     'provinces' => $provinces,
        //     'masukData' => $masukData,
        //     'keluarData' => $keluarData,
        // ]);

        $data = Budget::select(
            'provinces.name as province_name',
            DB::raw('SUM(CASE WHEN status = "Masuk" THEN amount ELSE 0 END) as total_masuk'),
            DB::raw('SUM(CASE WHEN status = "Keluar" THEN amount ELSE 0 END) as total_keluar')
        )
        ->join('provinces', 'budgets.province_id', '=', 'provinces.id')
        ->whereYear('budgets.created_at', $year)
        ->groupBy('province_name')
        ->get();
    
        // Format data untuk Chart.js
        $provinces = $data->pluck('province_name');
        $masukData = $data->pluck('total_masuk');
        $keluarData = $data->pluck('total_keluar');
    
        return response()->json([
            'provinces' => $provinces,
            'masukData' => $masukData,
            'keluarData' => $keluarData,
        ]);
    }
}
