<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
                    'slug' => 'sate-kambing-polos',
                    'description' => '10 Tusuk Sate Full Daging kambing muda empuk bumbu rempah khas Be Ba Lung.',
                    'price' => 50000,
                    'image' => 'sate_kambing_polos.jpg',
                    'badge' => 'BEST SELLER',
                    'is_available' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Sate Kambing (Campur)',
                    'slug' => 'sate-kambing-campur',
                    'description' => '10 Tusuk Sate Daging + Ati / Lemak gurih renyah aroma panggangan khas.',
                    'price' => 45000,
                    'image' => 'sate_kambing_campur.jpg',
                    'badge' => 'FAVORIT',
                    'is_available' => true,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Tongseng Kambing',
                    'slug' => 'tongseng-kambing',
                    'description' => 'Olahan daging kambing kuah tongseng gurih segar dengan irisan kol dan tomat.',
                    'price' => 35000,
                    'image' => 'tongseng_kambing.jpg',
                    'badge' => 'REKOMENDASI',
                    'is_available' => true,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Sop Kambing',
                    'slug' => 'sop-kambing',
                    'description' => 'Kuah bening rempah harum segar dengan potongan daging dan iga kambing lembut.',
                    'price' => 30000,
                    'image' => 'sop_kambing.jpg',
                    'badge' => 'SEGAR GURIH',
                    'is_available' => true,
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Gulai Kambing',
                    'slug' => 'gulai-kambing',
                    'description' => 'Gulai kambing kuah santan kental rempah istimewa yang gurih dan sedap.',
                    'price' => 30000,
                    'image' => 'gulai_kambing.jpg',
                    'badge' => null,
                    'is_available' => true,
                    'sort_order' => 5,
                ],
                [
                    'name' => 'Sate Ayam',
                    'slug' => 'sate-ayam',
                    'description' => 'Sate daging ayam bakar bumbu kacang gurih manis dengan taburan bawang goreng.',
                    'price' => 20000,
                    'image' => 'sate_ayam.jpg',
                    'badge' => null,
                    'is_available' => true,
                    'sort_order' => 6,
                ],
                [
                    'name' => 'Nasi Putih',
                    'slug' => 'nasi-putih',
                    'description' => 'Satu porsi nasi putih hangat pulen harum.',
                    'price' => 6000,
                    'image' => 'nasi_putih.jpg',
                    'badge' => null,
                    'is_available' => true,
                    'sort_order' => 7,
                ],
                [
                    'name' => 'Nasi Gurih',
                    'slug' => 'nasi-gurih',
                    'description' => 'Nasi gurih rempah santan daun jeruk dengan taburan bawang goreng.',
                    'price' => 7500,
                    'image' => 'nasi_gurih.jpg',
                    'badge' => 'GURIH',
                    'is_available' => true,
                    'sort_order' => 8,
                ],
            ];

            foreach ($makananItems as $item) {
                Menu::updateOrCreate(
                    ['slug' => $item['slug']],
                    array_merge($item, ['category_id' => $makanan->id])
                );
            }
        }

        if ($minuman) {
            $minumanItems = [
                [
                    'name' => 'Air Putih',
                    'slug' => 'air-putih',
                    'description' => 'Air mineral segar higienis pelepas dahaga.',
                    'price' => 2000,
                    'image' => 'air_putih.jpg',
                    'badge' => null,
                    'is_available' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Teh Tawar',
                    'slug' => 'teh-tawar',
                    'description' => 'Teh tawar hangat harum melati menyegarkan.',
                    'price' => 2000,
                    'image' => 'teh_tawar.jpg',
                    'badge' => 'HANGAT',
                    'is_available' => true,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Es Teh Tawar',
                    'slug' => 'es-teh-tawar',
                    'description' => 'Es teh tawar dingin segar pelepas dahaga.',
                    'price' => 3000,
                    'image' => 'es_teh_tawar.jpg',
                    'badge' => null,
                    'is_available' => true,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Es Teh Manis',
                    'slug' => 'es-teh-manis',
                    'description' => 'Es teh manis segar wangi melati asli.',
                    'price' => 4000,
                    'image' => 'es_teh_manis.jpg',
                    'badge' => 'SEGAR',
                    'is_available' => true,
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Air Jeruk / Panas',
                    'slug' => 'air-jeruk-panas',
                    'description' => 'Perasan jeruk murni hangat kaya vitamin C.',
                    'price' => 8000,
                    'image' => 'jeruk_panas.jpg',
                    'badge' => 'HANGAT',
                    'is_available' => true,
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Es Jeruk',
                    'slug' => 'es-jeruk',
                    'description' => 'Perasan jeruk segar asli dingin nikmat.',
                    'price' => 10000,
                    'image' => 'es_jeruk.jpg',
                    'badge' => 'FAVORIT',
                    'is_available' => true,
                    'sort_order' => 5,
                ],
                [
                    'name' => 'Teh Poci',
                    'slug' => 'teh-poci',
                    'description' => 'Teh poci tanah liat tradisional disajikan hangat dengan gula batu.',
                    'price' => 15000,
                    'image' => 'teh_poci.jpg',
                    'badge' => 'KLASIK',
                    'is_available' => true,
                    'sort_order' => 6,
                ],
                [
                    'name' => 'Kopi Toebroek',
                    'slug' => 'kopi-toebroek',
                    'description' => 'Kopi hitam tubruk biji kopi nusantara pilihan harum mantap.',
                    'price' => 5000,
                    'image' => 'kopi_toebroek.jpg',
                    'badge' => 'MANTAP',
                    'is_available' => true,
                    'sort_order' => 7,
                ],
            ];

            foreach ($minumanItems as $item) {
                Menu::updateOrCreate(
                    ['slug' => $item['slug']],
                    array_merge($item, ['category_id' => $minuman->id])
                );
            }
        }

        $paket = Category::where('slug', 'paket')->first();
        if ($paket) {
            $paketItems = [
                [
                    'name' => 'Paket Hemat Komplit',
                    'slug' => 'paket-hemat',
                    'description' => 'Paket komplit: Nasi Putih + Tongseng Kambing + 5 Tusuk Sate Kambing + Es Teh Manis.',
                    'price' => 22000,
                    'image' => 'paket_murah.jpg',
                    'badge' => 'HEMAT 22RB',
                    'is_available' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Paket Nasi Kotak Bento',
                    'slug' => 'paket-bento-syukuran',
                    'description' => 'Kemasan bento premium: Nasi pulen, Sate kambing empuk, Gule cup, Kerupuk & Buah.',
                    'price' => 28000,
                    'image' => 'paket_bento.jpg',
                    'badge' => 'BENTO KOMPLIT',
                    'is_available' => true,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Paket Kenyang Sate & Gulai',
                    'slug' => 'paket-kenyang-sate-gulai',
                    'description' => '1 Porsi Nasi Putih + 5 Tusuk Sate Kambing + Gulai Kambing Hangat + Es Teh Manis.',
                    'price' => 35000,
                    'image' => 'paket_murah.jpg',
                    'badge' => 'PAKET KENYANG',
                    'is_available' => true,
                    'sort_order' => 3,
                ],
            ];

            foreach ($paketItems as $item) {
                Menu::updateOrCreate(
                    ['slug' => $item['slug']],
                    array_merge($item, ['category_id' => $paket->id])
                );
            }
        }
    }
}
