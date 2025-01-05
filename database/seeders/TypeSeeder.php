<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type::create([
            'name' => 'MAKAN & MINUM'
        ]);
        Type::create([
            'name' => 'HIBURAN'
        ]);
        Type::create([
            'name' => 'VOUCHER HOTEL'
        ]);
        Type::create([
            'name' => 'SEWA KENDARAAN'
        ]);
        Type::create([
            'name' => 'SPONSORSHIP'
        ]);
    }
}
