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

class WelcomeController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->role_id != null && Auth::user()->role->name == 'Administrator') {
            return redirect()->route('admin');
        } else {
            $provinces = Province::all();
            $types = Type::all();
            $step1Data = session('step1_data', []);
            $step2Data = session('step2_data', []);
            $step3Data = session('step3_data', []);

            return view('welcome', compact('provinces', 'types', 'step1Data', 'step2Data', 'step3Data'));
        }
    }

    public function saveStep1(Request $request)
    {
        $validatedData = $request->validate([
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
            'peserta' => 'nullable|array',
            'peserta.*.nama_pelanggan' => 'nullable',
            'peserta.*.internal_icon' => 'nullable',
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
            'topik' => 'required',
            'aktivitas' => 'required',
            'target_pelaksanaan' => 'required',
        ]);

        $request->session()->put('step3_data', $validatedData);
        return response()->json(['message' => 'Step 3 saved']);
    }

    public function store(Request $request)
    {
        $step1Data = $request->session()->get('step1_data');
        $step2Data = $request->session()->get('step2_data');
        $step3Data = $request->session()->get('step3_data');

        if (!$step1Data || !$step2Data || !$step3Data) {
            return response()->json(['error' => 'Missing session data'], 422);
        }

        try {
            DB::beginTransaction();

            if ($request->input('user_id') === 'manual') {
                $user = User::create([
                    'name' => $step1Data['nama_lengkap'],
                    'email' => $step1Data['email'],
                    'role_id' => 2, 
                    'province_id' => $step1Data['province_id'],
                ]);
                $userId = $user->id;
            } else {
                $userId = $request->input('user_id');
                
            }

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);
            $tipe = Type::where('id', $step2Data['type_id'])->first();

            $pdfData = [
                'hari' => $step2Data['hari'],
                'tanggal' => $step2Data['tanggal'],
                'waktu' => $step2Data['waktu'],
                'tipe' => $tipe['name'],
                'nilai_entertain' => $step2Data['nilai_entertain'],
                'revenue' => $step2Data['revenue'],
                'pelanggan' => $step2Data['pelanggan'],
                'peserta' => $step2Data['peserta'],
                'topik' => $step3Data['topik'],
                'aktivitas' => $step3Data['aktivitas'],
                'target_pelaksanaan' => $step3Data['target_pelaksanaan'],
                'nama_account_manager' => $step1Data['nama_account_manager'],
                'nama_kanwil_manager' => $step1Data['nama_kanwil_manager'],
            ];

            $html = view('pdf-entertain', $pdfData)->render();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $request->session()->forget(['step1_data', 'step2_data', 'step3_data']);

            $output = $dompdf->output();
            $fileName = 'form_entertain_' . $step1Data['nama_lengkap'] . '_' . time() . '.pdf';

            $userEntertain = UserEntertain::create([
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
                'topik' => $step3Data['topik'],
                'aktivitas' => $step3Data['aktivitas'],
                'target_pelaksanaan' => $step3Data['target_pelaksanaan'],
            ]);

            if (!empty($step2Data['peserta'])) {
                foreach ($step2Data['peserta'] as $peserta) {
                    if (!empty($peserta['nama_pelanggan'])) {
                        UserEntertainPeserta::create([
                            'user_entertain_id' => $userEntertain->id,
                            'nama_pelanggan' => $peserta['nama_pelanggan'],
                            'internal_icon' => $peserta['internal_icon'],
                        ]);
                    }
                }
            }

            DB::commit();

            // Return response dengan PDF content sebagai base64
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'redirect' => route('welcome'),
                'pdf_content' => base64_encode($output),
                'file_name' => $fileName,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function getUsersByProvince($province_id)
    {
        $users = User::where('province_id', $province_id)
            ->where('role_id', 2)
            ->select('id', 'name', 'email')
            ->get();
        
        return response()->json($users);
    }
}
