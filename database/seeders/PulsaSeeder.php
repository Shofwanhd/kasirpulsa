<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PulsaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pulsas')->insert([
            [
                'kategori_pulsa_id' => 1,
                'pulsa' => 'Pulsa 10.000',
                'harga' => 12000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_pulsa_id' => 1,
                'pulsa' => 'Pulsa 20.000',
                'harga' => 22000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_pulsa_id' => 1,
                'pulsa' => 'Pulsa 50.000',
                'harga' => 52000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
