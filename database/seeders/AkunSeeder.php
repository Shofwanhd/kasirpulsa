<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('akuns')->insert([
            [
                'nama_akun' => 'Kas Tunai',
                'kategori_akun_id' => '1',
                'saldo_awal' => '2000000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_akun' => 'BCA',
                'kategori_akun_id' => '2',
                'saldo_awal' => '5000000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_akun' => 'BRI',
                'kategori_akun_id' => '2',
                'saldo_awal' => '3000000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_akun' => 'Mandiri',
                'kategori_akun_id' => '2',
                'saldo_awal' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_akun' => 'BNI',
                'kategori_akun_id' => '2',
                'saldo_awal' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_akun' => 'Dana',
                'kategori_akun_id' => '3',
                'saldo_awal' => '1500000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_akun' => 'OVO',
                'kategori_akun_id' => '3',
                'saldo_awal' => '1500000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_akun' => 'Gopay',
                'kategori_akun_id' => '3',
                'saldo_awal' => '1500000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_akun' => 'Shopeepay',
                'kategori_akun_id' => '3',
                'saldo_awal' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_akun' => 'Server Pulsa',
                'kategori_akun_id' => '4',
                'saldo_awal' => '1000000',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
