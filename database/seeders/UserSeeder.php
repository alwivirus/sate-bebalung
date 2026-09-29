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
        // 1. Akun Master Developer
        User::updateOrCreate(
            ['username' => 'dev'],
            [
                'name' => 'Master Developer',
                'email' => 'dev@bebarung.com',
                'password' => Hash::make('121212'),
                'role' => 'developer',
            ]
        );

        // 2. Akun Rahasia Kasir Utama (Full Admin / Owner)
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin Kasir Utama / Owner',
                'email' => 'admin@bebarung.com',
                'password' => Hash::make('ownsate'),
                'role' => 'admin',
            ]
        );

        // 3. Akun Kasir 1
        User::updateOrCreate(
            ['username' => 'kasir'],
            [
                'name' => 'Kasir 1 (Operasional POS)',
                'email' => 'kasir@bebarung.com',
                'password' => Hash::make('sate'),
                'role' => 'kasir',
            ]
        );

        User::updateOrCreate(
            ['username' => 'kasir1'],
            [
                'name' => 'Kasir 1 (Operasional POS)',
                'email' => 'kasir1@bebarung.com',
                'password' => Hash::make('sate'),
                'role' => 'kasir',
            ]
        );
    }
}
