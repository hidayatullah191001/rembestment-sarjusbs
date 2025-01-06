<?php

namespace Database\Seeders;

use App\Models\MetaApp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MetaAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $settings = [
            ['field' => 'logo_large', 'value' => null],
            ['field' => 'logo_mini', 'value' => null],
            ['field' => 'app_name', 'value' => 'Aplikasi Saya'],
            ['field' => 'app_url', 'value' => config('app.url')], // URL aplikasi statis
            ['field' => 'app_footer', 'value' => 'Hak Cipta © 2025'],
        ];

        foreach ($settings as $setting) {
            MetaApp::updateOrCreate(['field' => $setting['field']], $setting);
        }
    }
}
