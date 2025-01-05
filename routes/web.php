<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetRelocationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Models\UserEntertainPeserta;
use Illuminate\Support\Facades\Route;

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

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/save-step-1', [WelcomeController::class, 'saveStep1']);
Route::post('/save-step-2', [WelcomeController::class, 'saveStep2']);
Route::post('/save-step-3', [WelcomeController::class, 'saveStep3']);
Route::post('/store-entertain', [WelcomeController::class, 'store'])->name('store-entertain');

// web.php atau api.php
Route::get('/get-users-by-province/{province_id}', [WelcomeController::class, 'getUsersByProvince'])->name('get-users-by-province');

Route::get('/cetakPDF', function () {
    $peserta1 = new UserEntertainPeserta();
    $peserta1->id = 1;
    $peserta1->user_entertain_id = 1;
    $peserta1->nama_pelanggan = 'John Doe';
    $peserta1->internal_icon = 'john.doe@example.com';
    $peserta1->created_at = now();

    $peserta2 = new UserEntertainPeserta();
    $peserta2->id = 2;
    $peserta1->user_entertain_id = 2;
    $peserta2->nama_pelanggan = 'Jane Doe';
    $peserta2->internal_icon = 'jane.doe@example.com';
    $peserta2->created_at = now();

    // Masukkan ke dalam array atau collection
    $peserta = [$peserta1, $peserta2];

    $pdfData = [
        'hari' => 'Senin',
        'tanggal' => '2024-12-12',
        'waktu' => '13:36',
        'tipe' => 'Voucher Hotel',
        'nilai_entertain' => '200000',
        'revenue' => '5000000',
        'pelanggan' => 'Setia Arya',
        'peserta' => $peserta,
        'topik' => 'Topik ',
        'aktivitas' => 'Aktivitas kedua',
        'target_pelaksanaan' => 'Target Pelaksaanaan 22',
        'nama_account_manager' => 'Budi Santoso',
        'nama_kanwil_manager' => 'Maruf Firaun',
    ];

    return view('pdf-entertain', $pdfData);
});

Route::group(['middleware' => ['auth', 'isadmin']], function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('admin');
    Route::resource('budget', BudgetController::class);
    Route::resource('user', UserController::class);
    Route::resource('province', ProvinceController::class);
    Route::resource('budget/relocation', BudgetRelocationController::class);
    Route::get('/api/relocations/{id}', [BudgetController::class, 'getRelocations']);
});

// Route::middleware(['auth', 'isadmin'])->group(function () {
//     // Route::get('/', [HomeController::class, 'index'])->name('admin');

//     Route::resource('budget', BudgetController::class);
//     Route::resource('user', BudgetController::class);

// });
