<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Outlet;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === AKUN DEFAULT (WAJIB ADA!) ===
        User::create([
            'username' => 'owner',
            'password' => bcrypt('owner123'),
            'role'     => 'owner',
        ]);

        User::create([
            'username' => 'supervisor',
            'password' => bcrypt('super123'),
            'role'     => 'supervisor',
        ]);

        User::create([
            'username' => 'gudang',
            'password' => bcrypt('gudang123'),
            'role'     => 'gudang',
        ]);

        // === 1 OUTLET CONTOH (BIAR BISA LANGSUNG PAKAI) ===
        $outlet = Outlet::create([
            'nama'      => 'Outlet Utama',
            'alamat'    => 'Jl. Contoh No. 123',
            'is_active' => true,
        ]);

        // === 1 KARYAWAN CONTOH (BIAR BISA TEST TRANSAKSI) ===
        User::create([
            'username'  => 'karyawan1',
            'password'  => bcrypt('karyawan123'),
            'role'      => 'karyawan',
            'outlet_id' => $outlet->id,
        ]);

        // Bahan, Produk, Stok, dll → KOSONGIN AJA!
        // Nanti ditambah lewat frontend (web/app) → langsung masuk database
    }
}