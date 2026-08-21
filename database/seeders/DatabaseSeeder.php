<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@bebarung.com'],
            [
                'name' => 'Admin Depot Be Ba Lung',
                'password' => Hash::make('admin123'),
            ]
        );

        $this->call([
            CategorySeeder::class,
            MenuSeeder::class,
        ]);
    }
}
