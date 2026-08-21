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
                    'name' => 'Sate Kambing Polos',
                    'description' => 'Sate daging kambing empuk tanpa lemak, bumbu kecap/kacang khas Be Ba Lung.',
                    'price' => 50000,
                    'image' => 'sate_kambing_polos.jpg',
                    'is_available' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Sate Kambing Campur',
                    'description' => 'Sate daging kambing campur lemak gurih dengan bumbu spesial.',
                    'price' => 45000,
                    'image' => 'sate_kambing_campur.jpg',
                    'is_available' => true,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Tongseng Kambing',
                    'description' => 'Olahan daging kambing berkuah santan gurih, kol segar, dan tomat.',
                    'price' => 35000,
                    'image' => 'tongseng_kambing.jpg',
                    'is_available' => true,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Sop Kambing',
                    'description' => 'Kuah bening rempah segar dengan potongan daging kambing lembut.',
                    'price' => 30000,
                    'image' => 'sop_kambing.jpg',
                    'is_available' => true,
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Gulai Kambing',
                    'description' => 'Gulai kuah kuning kental rempah istimewa dan daging kambing empuk.',
                    'price' => 30000,
                    'image' => 'gulai_kambing.jpg',
                    'is_available' => true,
                    'sort_order' => 5,
                ],
                [
                    'name' => 'Sate Ayam',
                    'description' => 'Sate daging ayam bakar bumbu kacang gurih manis dengan bawang goreng.',
                    'price' => 20000,
                    'image' => 'sate_ayam.jpg',
                    'is_available' => true,
                    'sort_order' => 6,
                ],
                [
                    'name' => 'Nasi Putih',
                    'description' => 'Nasi putih pulen hangat harum.',
                    'price' => 6000,
                    'image' => 'nasi_putih.jpg',
                    'is_available' => true,
                    'sort_order' => 7,
                ],
                [
                    'name' => 'Nasi Gurih',
                    'description' => 'Nasi gurih rempah santan dengan taburan bawang goreng harum.',
                    'price' => 7500,
                    'image' => 'nasi_gurih.jpg',
                    'is_available' => true,
                    'sort_order' => 8,
                ],
                [
                    'name' => 'Paket Murah',
                    'description' => 'Paket hemat nasi + sate/gulai porsi pas untuk santap siang.',
                    'price' => 22000,
                    'image' => 'paket_murah.jpg',
                    'is_available' => true,
                    'sort_order' => 9,
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
                    'name' => 'Air Putih',
                    'description' => 'Air mineral segar dan higienis.',
                    'price' => 2000,
                    'image' => 'air_putih.jpg',
                    'is_available' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Teh Tawar',
                    'description' => 'Teh hangat tawar aroma melati.',
                    'price' => 2000,
                    'image' => 'teh_tawar.jpg',
                    'is_available' => true,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Es Teh Tawar',
                    'description' => 'Es teh tawar dingin segar.',
                    'price' => 3000,
                    'image' => 'es_teh_tawar.jpg',
                    'is_available' => true,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Es Teh Manis',
                    'description' => 'Es teh manis segar pelepas dahaga.',
                    'price' => 4000,
                    'image' => 'es_teh_manis.jpg',
                    'is_available' => true,
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Jeruk Panas',
                    'description' => 'Perasan jeruk asli hangat dengan gula murni.',
                    'price' => 8000,
                    'image' => 'jeruk_panas.jpg',
                    'is_available' => true,
                    'sort_order' => 5,
                ],
                [
                    'name' => 'Es Jeruk',
                    'description' => 'Perasan jeruk segar asli dingin nikmat.',
                    'price' => 10000,
                    'image' => 'es_jeruk.jpg',
                    'is_available' => true,
                    'sort_order' => 6,
                ],
                [
                    'name' => 'Teh Poci',
                    'description' => 'Teh poci khas Jawa disajikan hangat dengan gula batu.',
                    'price' => 15000,
                    'image' => 'teh_poci.jpg',
                    'is_available' => true,
                    'sort_order' => 7,
                ],
                [
                    'name' => 'Kopi Toebroek',
                    'description' => 'Kopi hitam tubruk mantap aroma khas nusantara.',
                    'price' => 5000,
                    'image' => 'kopi_toebroek.svg',
                    'is_available' => true,
                    'sort_order' => 8,
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
