<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $makanan = Category::where('slug', 'makanan')->first();
        $minuman = Category::where('slug', 'minuman')->first();

        if ($makanan) {
            $makananItems = [
                [
                    'name' => 'Sate Kambing (Polos)',
                    'description' => '10 Tusuk Sate Full Daging kambing muda empuk bumbu rempah khas Be Ba Lung.',
                    'price' => 50000,
                    'image' => 'sate_kambing_polos.jpg',
                    'is_available' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Sate Kambing (Campur)',
                    'description' => '10 Tusuk Sate Daging + Ati / Lemak gurih renyah aroma panggangan khas.',
                    'price' => 45000,
                    'image' => 'sate_kambing_campur.jpg',
                    'is_available' => true,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Tongseng Kambing',
                    'description' => 'Olahan daging kambing kuah tongseng gurih segar dengan irisan kol dan tomat.',
                    'price' => 35000,
                    'image' => 'tongseng_kambing.jpg',
                    'is_available' => true,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Sop Kambing',
                    'description' => 'Kuah bening rempah harum segar dengan potongan daging dan iga kambing lembut.',
                    'price' => 30000,
                    'image' => 'sop_kambing.jpg',
                    'is_available' => true,
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Gulai Kambing',
                    'description' => 'Gulai kambing kuah santan kental rempah istimewa yang gurih dan sedap.',
                    'price' => 30000,
                    'image' => 'gulai_kambing.jpg',
                    'is_available' => true,
                    'sort_order' => 5,
                ],
                [
                    'name' => 'Sate Ayam',
                    'description' => 'Sate daging ayam bakar bumbu kacang gurih manis dengan taburan bawang goreng.',
                    'price' => 20000,
                    'image' => 'sate_ayam.jpg',
                    'is_available' => true,
                    'sort_order' => 6,
                ],
                [
                    'name' => 'Nasi Putih',
                    'description' => 'Satu porsi nasi putih hangat pulen harum.',
                    'price' => 6000,
                    'image' => 'nasi_putih.jpg',
                    'is_available' => true,
                    'sort_order' => 7,
                ],
                [
                    'name' => 'Nasi Gurih',
                    'description' => 'Nasi gurih rempah santan daun jeruk dengan taburan bawang goreng.',
                    'price' => 7500,
                    'image' => 'nasi_gurih.jpg',
                    'is_available' => true,
                    'sort_order' => 8,
                ],
            ];

            foreach ($makananItems as $item) {
                Menu::updateOrCreate(
                    ['name' => $item['name'], 'category_id' => $makanan->id],
                    $item
                );
            }
        }

        if ($minuman) {
            $minumanItems = [
                [
                    'name' => 'Air Putih / Teh Tawar',
                    'description' => 'Air mineral / teh tawar hangat segar higienis.',
                    'price' => 2000,
                    'image' => 'air_putih.jpg',
                    'is_available' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Es Teh Tawar',
                    'description' => 'Es teh tawar dingin segar pelepas dahaga.',
                    'price' => 3000,
                    'image' => 'es_teh_tawar.jpg',
                    'is_available' => true,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Es Teh Manis',
                    'description' => 'Es teh manis segar wangi melati asli.',
                    'price' => 4000,
                    'image' => 'es_teh_manis.jpg',
                    'is_available' => true,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Air Jeruk / Panas',
                    'description' => 'Perasan jeruk murni hangat kaya vitamin C.',
                    'price' => 8000,
                    'image' => 'jeruk_panas.jpg',
                    'is_available' => true,
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Es Jeruk',
                    'description' => 'Perasan jeruk segar asli dingin nikmat.',
                    'price' => 10000,
                    'image' => 'es_jeruk.jpg',
                    'is_available' => true,
                    'sort_order' => 5,
                ],
                [
                    'name' => 'Teh Poci',
                    'description' => 'Teh poci tanah liat tradisional disajikan hangat dengan gula batu.',
                    'price' => 15000,
                    'image' => 'teh_poci.jpg',
                    'is_available' => true,
                    'sort_order' => 6,
                ],
                [
                    'name' => 'Kopi Toebroek',
                    'description' => 'Kopi hitam tubruk biji kopi nusantara pilihan harum mantap.',
                    'price' => 5000,
                    'image' => 'kopi_toebroek.svg',
                    'is_available' => true,
                    'sort_order' => 7,
                ],
            ];

            foreach ($minumanItems as $item) {
                Menu::updateOrCreate(
                    ['name' => $item['name'], 'category_id' => $minuman->id],
                    $item
                );
            }
        }
    }
}
