<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'MENU MAKANAN',
                'slug' => 'makanan',
                'icon' => '🍱',
                'sort_order' => 1,
            ],
            [
                'name' => 'MINUMAN',
                'slug' => 'minuman',
                'icon' => '☕',
                'sort_order' => 2,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
