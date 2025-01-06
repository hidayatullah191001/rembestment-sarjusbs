<?php

namespace App\Http\Controllers;

use App\Models\MetaApp;
use Illuminate\Http\Request;

class MetaAppController extends Controller
{
    public function index()
    {
        $settings = MetaApp::all()->pluck('value', 'field');
        return view('pages.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_footer' => 'required|string|max:255',
            'icon_app' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'logo_large' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'logo_mini' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fields = ['app_name', 'app_footer'];

        foreach ($fields as $field) {
            MetaApp::updateOrCreate(
                ['field' => $field],
                ['value' => $request->input($field)]
            );
        }

        // Upload logo_large
        if ($request->hasFile('icon_app')) {
            $path = $request->file('icon_app')->store('settings', 'public');
            MetaApp::updateOrCreate(['field' => 'icon_app'], ['value' => $path]);
        }
        
        if ($request->hasFile('logo_large')) {
            $path = $request->file('logo_large')->store('settings', 'public');
            MetaApp::updateOrCreate(['field' => 'logo_large'], ['value' => $path]);
        }

        // Upload logo_mini
        if ($request->hasFile('logo_mini')) {
            $path = $request->file('logo_mini')->store('settings', 'public');
            MetaApp::updateOrCreate(['field' => 'logo_mini'], ['value' => $path]);
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}
