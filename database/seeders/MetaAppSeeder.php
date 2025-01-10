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
            ['field' => 'logo_large', 'value' => 'settings/5WpeCmY5eFi9I4G6OINfuaS2bFClzl8Lu31EBD7d.png'],
            ['field' => 'logo_mini', 'value' => 'settings/uIs6JpnCmp3gonsx2Av83lujUcMOxTS8gSjfvW8F.png'],
            ['field' => 'app_name', 'value' => 'Rembestment'],
            ['field' => 'app_url', 'value' => env('APP_URL')], // URL aplikasi statis
            ['field' => 'app_footer', 'value' => 'Aplikasi Rembestment Sarjusbs'],
            ['field' => 'icon_app', 'value' => 'settings/En99lHrlSvfDEzevYz0XKIUfYLrF8ASrB5fz2N2p.png'],
            ['field' => 'nama_manager_pemasaran', 'value' => 'HIZMA JONATHAPOLI'],
            ['field' => 'jabatan_lengkap', 'value' => 'MANAGER PEMASARAN DAN PENJUALAN SBU SUMBASEL'],
        ];

        foreach ($settings as $setting) {
            MetaApp::updateOrCreate(['field' => $setting['field']], $setting);
        }
    }
}
