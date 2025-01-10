<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Province::create([
            'name' => 'Sumatera Selatan',
            'nama_manager_unit_layanan' => 'Setia Budi',
            'jabatan' => ' MANAGER KANTOR PERWAKILAN SUMSEL',
        ]);
        Province::create([
            'name' => 'Jambi',
            'nama_manager_unit_layanan' => 'Ahmad Syafii',
            'jabatan' => ' MANAGER KANTOR PERWAKILAN JAMBI',
        ]);
        Province::create([
            'name' => 'Kepulauan Bangka Belitung',
            'nama_manager_unit_layanan' => 'Inaka Tomo',
            'jabatan' => ' MANAGER KANTOR PERWAKILAN BABEL',
        ]);
        Province::create([
            'name' => 'Bengkulu',
            'nama_manager_unit_layanan' => 'Narto Masmuki',
            'jabatan' => ' MANAGER KANTOR PERWAKILAN BENGKULU',
        ]);
        Province::create([
            'name' => 'Lampung',
            'nama_manager_unit_layanan' => 'Suryani Apik',
            'jabatan' => ' MANAGER KANTOR PERWAKILAN LAMPUNG',
        ]);
    }
}
