<?php

use App\Exports\UserEntertainExport;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetRelocationController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MetaAppController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserEntertainController;
use App\Http\Controllers\WelcomeController;
use App\Models\UserEntertainPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Auth::routes(['register' => false]);

Route::post('/save-step-1', [WelcomeController::class, 'saveStep1']);
Route::post('/save-step-2', [WelcomeController::class, 'saveStep2']);
Route::post('/save-step-3', [WelcomeController::class, 'saveStep3']);
Route::post('/save-step-4', [WelcomeController::class, 'saveStep4']);
Route::post('/store-entertain', [WelcomeController::class, 'store'])->name('store-entertain');

Route::get('/get-users-by-province/{province_id}', [WelcomeController::class, 'getUsersByProvince'])->name('get-users-by-province');

Route::group(['middleware' => ['auth', 'isadmin']], function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('admin');
    Route::resource('budget', BudgetController::class)->except(['edit', 'update']);
    Route::resource('user', UserController::class)->except('show');
    Route::get('profile', [UserController::class, 'profile'])->name('profile.index');
    Route::put('profile', [UserController::class, 'profileUpdate'])->name('profile.update');
    Route::resource('province', ProvinceController::class)->except('show');
    Route::resource('budget/relocation', BudgetRelocationController::class)->only(['store']);
    Route::resource('entertain', UserEntertainController::class)->only(['index', 'show','destroy']);
    Route::get('/api/generatePdf/{id}', [UserEntertainController::class, 'generatePdf']);
    Route::get('/api/relocations/{id}', [BudgetController::class, 'getRelocations']);
    Route::get('/get_entertain_data', [HomeController::class, 'getEntertainData']);
    Route::get('/get_entertain_user_input_data', [HomeController::class, 'getEntertainUserInputData']);
    Route::get('/get_budget_data', [HomeController::class, 'getBudgetData']);
    Route::get('/export-user-entertain', function (Request $request) {
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        if ($start_date || $end_date) {
            $fileName = 'User Entertain - ' . 
                        ($start_date ?: 'StartDate') . ' - ' . 
                        ($end_date ?: 'EndDate') . '.xlsx';
        } else {
            $fileName = 'User Entertain - ' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        }
        $fileName = preg_replace('/[^A-Za-z0-9_\-\. ]/', '_', $fileName);
        return Excel::download(new UserEntertainExport($start_date, $end_date), $fileName);
    });
    Route::get('/settings', [MetaAppController::class, 'index'])->name('settings.index');
    Route::post('/settings', [MetaAppController::class, 'update'])->name('settings.update');
});

Route::fallback([Controller::class, 'error404']);

