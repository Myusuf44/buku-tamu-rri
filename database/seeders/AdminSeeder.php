<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat akun Super Admin
        User::updateOrCreate(
            ['email' => 'admin@rri-bukittinggi.co.id'], // Kunci unik pencarian
            [
                'name'     => 'Super Administrator RRI',
                'nip'      => '19900101 202203 1 001', // NIP/NIK default (pastikan kolom nip sudah ada dari migrasi tambahan Anda)
                'password' => Hash::make('password123'), // Default password
                'role'     => 'super_admin', // Ditetapkan sebagai Super Admin
            ]
        );

        // Contoh membuat akun Admin Biasa (Opsional)
        User::updateOrCreate(
            ['email' => 'staff.admin@rri-bukittinggi.co.id'],
            [
                'name'     => 'Admin Biasa RRI',
                'nip'      => '19950202 202403 2 002',
                'password' => Hash::make('password123'),
                'role'     => 'admin', // Ditetapkan sebagai Admin biasa
            ]
        );
    }
}