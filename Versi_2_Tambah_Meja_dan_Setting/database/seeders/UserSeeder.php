<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Rahasia Kasir Utama (Full Admin)
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Kasir Utama (Owner)',
                'email' => 'admin@bebalung.com',
                'password' => Hash::make('bebalung1234'),
                'role' => 'admin',
            ]
        );

        // 2. Akun Kasir Kasir 1
        User::updateOrCreate(
            ['username' => 'kasir1'],
            [
                'name' => 'Kasir 1 (Shift Pagi/Malam)',
                'email' => 'kasir@bebalung.com',
                'password' => Hash::make('kasir1234'),
                'role' => 'kasir',
            ]
        );
    }
}
