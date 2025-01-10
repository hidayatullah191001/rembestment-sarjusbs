<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Type;
use App\Models\User;
use App\Models\UserEntertain;
use App\Models\UserEntertainPeserta;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class WelcomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role_id != null && Auth::user()->role_id == 1) {
                return redirect()->route('admin');
            } else {
                Auth::logout();
                return redirect()->route('login')->with('error', 'You don\'t have permission to access this.');
            }
        } else {
            $provinces = Province::all();
            $types = Type::all();
            $step1Data = session('step1_data', []);
            $step2Data = session('step2_data', []);
            $step3Data = session('step3_data', []);
            $step4Data = session('step4_data', []);
            return view('welcome', compact('provinces', 'types', 'step1Data', 'step2Data', 'step3Data', 'step4Data'));
        }
    }

    public function saveStep1(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'province_id' => 'required',
            'nama_lengkap' => 'required',
            'email' => 'required|email',
            'nama_account_manager' => 'required',
            'nama_kanwil_manager' => 'required',
        ]);

        $request->session()->put('step1_data', $validatedData);
        return response()->json(['message' => 'Step 1 saved']);
    }

    public function saveStep2(Request $request)
    {
        $validatedData = $request->validate([
            'hari' => 'required',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'type_id' => 'required',
            'nilai_entertain_value' => 'required|numeric',
            'revenue_value' => 'required|numeric',
            'pelanggan' => 'required',
        ]);

        $validatedData['nilai_entertain'] = $validatedData['nilai_entertain_value'];
        $validatedData['revenue'] = $validatedData['revenue_value'];

        unset($validatedData['nilai_entertain_value']);
        unset($validatedData['revenue_value']);

        $request->session()->put('step2_data', $validatedData);
        return response()->json(['message' => 'Step 2 saved']);
    }

    public function saveStep3(Request $request)
    {
        $validatedData = $request->validate([
            'peserta' => 'nullable|array',
            'peserta.*.nama_pelanggan' => 'nullable',
            'peserta.*.internal_icon' => 'nullable',
        ]);

        $request->session()->put('step3_data', $validatedData);
        return response()->json(['message' => 'Step 3 saved']);
    }

    public function saveStep4(Request $request)
    {
        $validatedData = $request->validate([
            'topik' => 'required',
            'aktivitas' => 'required',
            'target_pelaksanaan' => 'required',
            'upload_file_1' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx,xls,xlsx,zip,rar|max:10240',
            'upload_file_2' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx,xls,xlsx,zip,rar|max:10240',
        ]);

        // Simpan file ke folder sementara
        if ($request->hasFile('upload_file_1')) {
            $path1 = $request->file('upload_file_1')->store('tmp');
            $validatedData['upload_file_1'] = $path1;
        }

        if ($request->hasFile('upload_file_2')) {
            $path2 = $request->file('upload_file_2')->store('tmp');
            $validatedData['upload_file_2'] = $path2;
        }

        // Simpan data di sesi
        $request->session()->put('step4_data', $validatedData);

        return response()->json(['message' => 'Step 4 saved']);
    }

    // public function store(Request $request)
    // {
    //     $step1Data = $request->session()->get('step1_data');
    //     $step2Data = $request->session()->get('step2_data');
    //     $step3Data = $request->session()->get('step3_data');
    //     $step4Data = $request->session()->get('step4_data');

    //     if (!$step1Data || !$step2Data || !$step3Data || !$step4Data) {
    //         return response()->json(['error' => 'Missing session data'], 422);
    //     }

    //     try {
    //         DB::beginTransaction();
    //         $tempFiles = [];
    //         if ($request->input('user_id') === 'manual') {
    //             $user = User::create([
    //                 'name' => $step1Data['nama_lengkap'],
    //                 'email' => $step1Data['email'],
    //                 'role_id' => 2,
    //                 'password' => Hash::make('password'),
    //                 'province_id' => $step1Data['province_id'],
    //             ]);
    //             $userId = $user->id;
    //         } else {
    //             $userId = $request->input('user_id');

    //         }

    //         $options = new Options();
    //         $options->set('isHtml5ParserEnabled', true);
    //         $dompdf = new Dompdf($options);
    //         $tipe = Type::where('id', $step2Data['type_id'])->first();

    //         if (!empty($step4Data['upload_file_1'])) {
    //             $file1 = $step4Data['upload_file_1'];
    //             $tempFiles[] = $file1;
    //             $finalPath1 = 'uploads/' . basename($file1);
    //             Storage::move($file1, $finalPath1);
    //             $step4Data['upload_file_1'] = $finalPath1;
    //         }

    //         if (!empty($step4Data['upload_file_2'])) {
    //             $file2 = $step4Data['upload_file_2'];
    //             $tempFiles[] = $file2;
    //             $finalPath2 = 'uploads/' . basename($file2);
    //             Storage::move($file2, $finalPath2);
    //             $step4Data['upload_file_2'] = $finalPath2;
    //         }
    //         $user = User::where('id', $userId)->first();
    //         $pdfData = [
    //             'hari' => $step2Data['hari'],
    //             'tanggal' => $step2Data['tanggal'],
    //             'waktu' => $step2Data['waktu'],
    //             'tipe' => $tipe['name'],
    //             'nilai_entertain' => $step2Data['nilai_entertain'],
    //             'revenue' => $step2Data['revenue'],
    //             'pelanggan' => $step2Data['pelanggan'],
    //             'peserta' => $step3Data['peserta'],
    //             'topik' => $step4Data['topik'],
    //             'aktivitas' => $step4Data['aktivitas'],
    //             'target_pelaksanaan' => $step4Data['target_pelaksanaan'],
    //             'nama_kanwil_manager' => $step1Data['nama_kanwil_manager'],
    //             'jabatan_kanwil' => $user->province->jabatan,
    //             'nama_account_manager' => $step1Data['nama_account_manager'],
    //             'upload_file_1' => $step4Data['upload_file_1'] ?? null,
    //             'upload_file_2' => $step4Data['upload_file_2'] ?? null,
    //         ];

    //         $html = view('pdf-entertain', $pdfData)->render();
    //         $dompdf->loadHtml($html);
    //         $dompdf->setPaper('A4', 'portrait');
    //         $dompdf->render();

    //         $request->session()->forget(['step1_data', 'step2_data', 'step3_data']);

    //         $output = $dompdf->output();
    //         $fileName = 'form_entertain_' . $step1Data['nama_lengkap'] . '_' . time() . '.pdf';
    //         $userEntertain = UserEntertain::create([
    //             'user_id' => $userId,
    //             'nama_account_manager' => $step1Data['nama_account_manager'],
    //             'nama_kanwil_manager' => $step1Data['nama_kanwil_manager'],
    //             'hari' => $step2Data['hari'],
    //             'tanggal' => $step2Data['tanggal'],
    //             'waktu' => $step2Data['waktu'],
    //             'type_id' => $step2Data['type_id'],
    //             'nilai_entertain' => $step2Data['nilai_entertain'],
    //             'revenue' => $step2Data['revenue'],
    //             'pelanggan' => $step2Data['pelanggan'],
    //             'topik' => $step4Data['topik'],
    //             'aktivitas' => $step4Data['aktivitas'],
    //             'target_pelaksanaan' => $step4Data['target_pelaksanaan'],
    //             'upload_file_1' => $step4Data['upload_file_1'] ?? null,
    //             'upload_file_2' => $step4Data['upload_file_2'] ?? null,
    //         ]);

    //         if (!empty($step3Data['peserta'])) {
    //             foreach ($step3Data['peserta'] as $peserta) {
    //                 if (!empty($peserta['nama_pelanggan'])) {
    //                     UserEntertainPeserta::create([
    //                         'user_entertain_id' => $userEntertain->id,
    //                         'nama_pelanggan' => $peserta['nama_pelanggan'],
    //                         'internal_icon' => $peserta['internal_icon'],
    //                     ]);
    //                 }
    //             }
    //         }

    //         foreach ($tempFiles as $tempFile) {
    //             if (Storage::exists($tempFile)) {
    //                 Storage::delete($tempFile);
    //             }
    //         }

    //         DB::commit();

    //         // Return response dengan PDF content sebagai base64
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Data berhasil disimpan',
    //             'redirect' => route('welcome'),
    //             'pdf_content' => base64_encode($output),
    //             'file_name' => $fileName,
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return response()->json(
    //             [
    //                 'success' => false,
    //                 'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
    //             ],
    //             500,
    //         );
    //     }
    // }

    public function store(Request $request)
    {
        $step1Data = $request->session()->get('step1_data');
        $step2Data = $request->session()->get('step2_data');
        $step3Data = $request->session()->get('step3_data');
        $step4Data = $request->session()->get('step4_data');

        if (!$step1Data || !$step2Data || !$step3Data || !$step4Data) {
            return response()->json(['error' => 'Missing session data'], 422);
        }

        try {
            DB::beginTransaction();

            // 1. Handle user creation first
            $userId = $this->handleUserCreation($request, $step1Data);

            // 2. Process files early and parallel
            $filesPaths = $this->processFiles($step4Data);

            // 3. Get type information early
            $tipe = Type::find($step2Data['type_id']);
            if (!$tipe) {
                throw new \Exception('Type not found');
            }

            // 4. Create entertainment record
            $userEntertain = $this->createEntertainmentRecord($userId, $step1Data, $step2Data, $step4Data, $filesPaths);

            // 5. Process peserta in bulk if exists
            if (!empty($step3Data['peserta'])) {
                $this->createPesertaRecords($userEntertain->id, $step3Data['peserta']);
            }

            // 6. Generate PDF with optimized data
            $pdfContent = $this->generatePDF(array_merge($this->preparePdfData($step1Data, $step2Data, $step3Data, $step4Data, $tipe, $userId), ['upload_files' => $filesPaths]));

            $fileName = 'form_entertain_' . $step1Data['nama_lengkap'] . '_' . time() . '.pdf';

            // Cleanup session after successful processing
            $request->session()->forget(['step1_data', 'step2_data', 'step3_data', 'step4_data']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'redirect' => route('welcome'),
                'pdf_content' => base64_encode($pdfContent),
                'file_name' => $fileName,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            // Cleanup any uploaded files in case of error
            $this->cleanupFiles($step4Data);

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    private function handleUserCreation($request, $step1Data)
    {
        if ($request->input('user_id') === 'manual') {
            $user = User::create([
                'name' => $step1Data['nama_lengkap'],
                'email' => $step1Data['email'],
                'role_id' => 2,
                'password' => Hash::make('password'),
                'province_id' => $step1Data['province_id'],
            ]);
            return $user->id;
        }
        return $request->input('user_id');
    }

    private function processFiles($step4Data)
    {
        $filesPaths = [
            'upload_file_1' => null,
            'upload_file_2' => null,
        ];

        if (!empty($step4Data['upload_file_1'])) {
            $file1 = $step4Data['upload_file_1'];
            $finalPath1 = 'uploads/' . basename($file1);
            Storage::move($file1, $finalPath1);
            $filesPaths['upload_file_1'] = $finalPath1;
        }

        if (!empty($step4Data['upload_file_2'])) {
            $file2 = $step4Data['upload_file_2'];
            $finalPath2 = 'uploads/' . basename($file2);
            Storage::move($file2, $finalPath2);
            $filesPaths['upload_file_2'] = $finalPath2;
        }

        return $filesPaths;
    }

    private function createEntertainmentRecord($userId, $step1Data, $step2Data, $step4Data, $filesPaths)
    {
        return UserEntertain::create([
            'user_id' => $userId,
            'nama_account_manager' => $step1Data['nama_account_manager'],
            'nama_kanwil_manager' => $step1Data['nama_kanwil_manager'],
            'hari' => $step2Data['hari'],
            'tanggal' => $step2Data['tanggal'],
            'waktu' => $step2Data['waktu'],
            'type_id' => $step2Data['type_id'],
            'nilai_entertain' => $step2Data['nilai_entertain'],
            'revenue' => $step2Data['revenue'],
            'pelanggan' => $step2Data['pelanggan'],
            'topik' => $step4Data['topik'],
            'aktivitas' => $step4Data['aktivitas'],
            'target_pelaksanaan' => $step4Data['target_pelaksanaan'],
            'upload_file_1' => $filesPaths['upload_file_1'],
            'upload_file_2' => $filesPaths['upload_file_2'],
        ]);
    }

    private function createPesertaRecords($entertainId, $peserta)
    {
        $pesertaRecords = [];
        foreach ($peserta as $p) {
            if (!empty($p['nama_pelanggan'])) {
                $pesertaRecords[] = [
                    'user_entertain_id' => $entertainId,
                    'nama_pelanggan' => $p['nama_pelanggan'],
                    'internal_icon' => $p['internal_icon'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($pesertaRecords)) {
            UserEntertainPeserta::insert($pesertaRecords); // Bulk insert
        }
    }

    private function preparePdfData($step1Data, $step2Data, $step3Data, $step4Data, $tipe, $userId)
    {
        return [
            'hari' => $step2Data['hari'],
            'tanggal' => $step2Data['tanggal'],
            'waktu' => $step2Data['waktu'],
            'tipe' => $tipe->name,
            'nilai_entertain' => $step2Data['nilai_entertain'],
            'revenue' => $step2Data['revenue'],
            'pelanggan' => $step2Data['pelanggan'],
            'peserta' => $step3Data['peserta'],
            'topik' => $step4Data['topik'],
            'aktivitas' => $step4Data['aktivitas'],
            'target_pelaksanaan' => $step4Data['target_pelaksanaan'],
            'nama_kanwil_manager' => $step1Data['nama_kanwil_manager'],
            'jabatan_kanwil' => User::find($userId)->province->jabatan,
            'nama_account_manager' => $step1Data['nama_account_manager'],
        ];
    }

    private function generatePDF($data)
    {   
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        $html = view('pdf-entertain', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    private function cleanupFiles($step4Data)
    {
        if (!empty($step4Data['upload_file_1']) && Storage::exists($step4Data['upload_file_1'])) {
            Storage::delete($step4Data['upload_file_1']);
        }
        if (!empty($step4Data['upload_file_2']) && Storage::exists($step4Data['upload_file_2'])) {
            Storage::delete($step4Data['upload_file_2']);
        }
    }
    public function getUsersByProvince($province_id)
    {
        $users = User::where('province_id', $province_id)->where('role_id', 2)->select('id', 'name', 'email')->get();

        return response()->json($users);
    }
}
