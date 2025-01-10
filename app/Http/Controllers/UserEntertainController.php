<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelper;
use App\Models\Province;
use App\Models\Type;
use App\Models\User;
use App\Models\UserEntertain;
use App\Models\UserEntertainPeserta;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserEntertainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userEntertains = UserEntertain::orderBy('created_at', 'desc')->get();
        return view('pages.entertains.index', compact('userEntertains'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = MyHelper::decodeID($id);
        $userEntertain = UserEntertain::find($id);
        return view('pages.entertains.show', ['userEntertain' => $userEntertain]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserEntertain $userEntertain)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserEntertain $userEntertain)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $decodeId = MyHelper::decodeID($id);
        $userEntertain = UserEntertain::find($decodeId);
        if (!$userEntertain) {
            return redirect()->route('entertain.show', $id)->with('error', 'Data entertain not found!');
        }
        Storage::delete([$userEntertain->upload_file_1, $userEntertain->upload_file_2]);
        $userEntertain->delete();
        return redirect()->route('entertain.index',)->with('success', 'Data entertain successfully deleted!');
    }

    public function generatePdf($id)
    {
        if (!$id) {
            return response()->json(['success' => false, 'message' => 'ID tidak ditemukan']);
        }
        $id = MyHelper::decodeID($id);
        $userEntertain = UserEntertain::with('peserta')->find($id);
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $tipe = Type::where('id', $userEntertain->type_id)->first();
        $peserta = UserEntertainPeserta::where('user_entertain_id', $userEntertain->id)->get();
        $upload_files = [
            'upload_file_1' => $userEntertain->upload_file_1,
            'upload_file_2' => $userEntertain->upload_file_2,
        ];
        $pdfData = [
            'hari' => $userEntertain->hari,
            'tanggal' => $userEntertain->tanggal,
            'waktu' => $userEntertain->waktu,
            'tipe' => $tipe->name,
            'nilai_entertain' => $userEntertain->nilai_entertain,
            'revenue' => $userEntertain->revenue,
            'pelanggan' => $userEntertain->pelanggan,
            'peserta' => $peserta,
            'topik' => $userEntertain->topik,
            'aktivitas' => $userEntertain->aktivitas,
            'target_pelaksanaan' => $userEntertain->target_pelaksanaan,
            'nama_account_manager' => $userEntertain->nama_account_manager,
            'jabatan_kanwil' => $userEntertain->user->province->jabatan,
            'nama_kanwil_manager' => $userEntertain->nama_kanwil_manager,
            'upload_files' => $upload_files,
            
        ];

        $html = view('pdf-entertain', $pdfData)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();
        $fileName = 'form_entertain_' . $userEntertain->user->name . '_' . time() . '.pdf';
        return response()->json([
            'success' => true,
            'message' => 'Berhasil generate PDF',
            'redirect' => route('entertain.index'),
            'pdf_content' => base64_encode($output),
            'file_name' => $fileName,
        ]);
    }
}
