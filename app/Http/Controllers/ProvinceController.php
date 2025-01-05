<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $provinces = Province::all();
        return view('pages.province.index', compact('provinces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.province.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors();
            return redirect()->route('province.create')
                ->withErrors($errors)
                ->withInput();
        }

        Province::create($request->all());

        return redirect()->route('province.index')->with('success', 'Data province successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Province $province)
    {
        return view('pages.province.form', compact('province'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Province $province)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);


        if ($validation->fails()) {
            $errors = $validation->errors();
            return redirect()->route('province.edit', $province->id)
                ->withErrors($errors)
                ->withInput();
        }

        $province->update($request->all());

        return redirect()->route('province.index')->with('success', 'Data province successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $province = Province::findOrFail($id);
            $province->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Data province successfully deleted.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
