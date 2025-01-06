<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['role', 'province'])->get();
        return view('pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all(); // Ganti dengan query roles Anda
        $provinces = Province::all(); // Ganti dengan query provinces Anda
        return view('pages.users.form',  compact('roles', 'provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|',
            'email' => 'required|string|email|unique:users',
            'province_id' => 'required',
            'role_id' => 'required',
        ],[
            'role_id.required' => 'The role field is required.',
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors();
            return redirect()->route('user.create')
                ->withErrors($errors)
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'),
            'province_id' => $request->province_id,
            'role_id' => $request->role_id,
            'is_active' => 1,
        ];

        User::create($data);

        return redirect()->route('user.index')->with('success', 'Data user successfully created');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $provinces = Province::all();
        return view('pages.users.form', compact('user', 'roles', 'provinces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' =>  ['required', 'email', 'string', Rule::unique('users')->ignore($user->id)],
            'province_id' => 'required',
            'role_id' => 'required',
        ],[
            'role_id.required' => 'The role field is required.',
        ]);


        if ($validation->fails()) {
            $errors = $validation->errors();

            return redirect()->route('user.edit', $user->id)
                ->withErrors($errors)
                ->withInput();
        }

        

        if($request->is_active == true){
            $active = 1;
        }else{
            $active = 0;
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'province_id' => $request->province_id,
            'role_id' => $request->role_id,
            'is_active' => $active,
        ];        

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'Data user successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Data user successfully deleted.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function profile()
    {
        $user = Auth::user();
        return view('pages.users.profile', compact('user'));
    }
    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update nama
        $user->name = $request->name;

        // Update password jika ada
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update photo_profile jika ada
        if ($request->hasFile('photo_profile')) {
            // Hapus foto lama jika ada
            if ($user->photo_profile) {
                Storage::delete($user->photo_profile);
            }

            // Simpan foto baru
            $path = $request->file('photo_profile')->store('photo_profiles', 'public');
            $user->photo_profile = $path;
        }

        $user->update();

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }
}
