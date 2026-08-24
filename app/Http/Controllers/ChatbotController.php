<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    /**
     * Handle incoming chatbot message from customer and return dynamic database recommendations.
     */
    public function message(Request $request): JsonResponse
    {
        $message = trim($request->input('message', ''));
        $tableNumber = $request->input('table_number', '01');
        $customerName = $request->input('customer_name', 'Pelanggan');

        if (empty($message)) {
            return response()->json([
                'success' => true,
                'reply' => "Halo Kak {$customerName}! 👋 Ada yang bisa Chef Bebalung bantu untuk pilihan menu lezat di Meja #{$tableNumber} hari ini?",
                'items' => [],
                'quick_replies' => $this->getDefaultQuickReplies(),
            ]);
        }

        $normalized = ' ' . strtolower($message) . ' ';

        // 0. Check for Profanity / Bad words / Bahasa Saru / Kotor (Sensor & Polite Reminder)
        if ($this->containsProfanity($normalized)) {
            $reply = "Waduh Kak {$customerName}, mohon gunakan bahasa yang sopan dan santun ya 🙏.\n\n";
            $reply .= "Chef Bebalung AI siap melayani Kakak dengan sepenuh hati untuk memilihkan hidangan sate kambing empuk, kuah gulai gurih, atau minuman segar terbaik dari dapur kami. Silakan pilih rekomendasi menu lezat di bawah ini ya Kak! ✨";

            return response()->json([
                'success' => true,
                'reply' => $reply,
                'items' => $this->getTopDatabaseMenus(3),
                'quick_replies' => $this->getDefaultQuickReplies(),
            ]);
        }

        // 1. Check for Info Restoran / Lokasi / Jam Operasional / Kontak (Priority restaurant info)
        if ($this->hasWordKeywords($normalized, ['lokasi', 'alamat', 'dimana', 'jam buka', 'jam operasional', 'buka jam', 'tutup jam', 'qris', 'pembayaran', 'nomor wa', 'whatsapp', 'no telp', 'telepon', 'reservasi'])) {
            return response()->json($this->handleRestaurantInfoIntent());
        }

        // 2. Check for Combo / Paket Komplit / Set meal
        if ($this->hasWordKeywords($normalized, ['combo', 'kombo', 'komplit', 'lengkap', 'pasangan', 'set meal', 'paket lengkap', 'paket juara'])) {
            return response()->json($this->handleComboIntent($customerName));
        }

        // 3. Check for Minuman / Haus (e.g. 'rekomendasi minuman', 'es teh', 'jus jeruk')
        if ($this->hasWordKeywords($normalized, ['minum', 'minuman', 'es jeruk', 'es teh', 'teh manis', 'teh tawar', 'teh poci', 'haus', 'segar dingin', 'seger dingin', 'jus', 'air putih', 'mineral'])) {
            return response()->json($this->handleDrinkIntent($normalized));
        }

        // 4. Check for Kuah / Hangat / Segar / Gulai / Tongseng / Sop
        if ($this->hasWordKeywords($normalized, ['kuah', 'sop', 'gulai', 'tongseng', 'hangat', 'anget', 'rempah', 'pedas', 'pedes', 'santan', 'berkuah', 'sup'])) {
            return response()->json($this->handleKuahIntent($normalized));
        }

        // 5. Check for Sate & Daging
        if ($this->hasWordKeywords($normalized, ['sate', 'kambing', 'daging', 'tanpa lemak', 'lemak', 'tusuk', 'bakar', 'polos', 'campur'])) {
            return response()->json($this->handleSateIntent($normalized));
        }

        // 6. Check for Budget / Price filter (e.g. 'di bawah 25rb', 'budget 30k', 'harga 20000')
        if ($budget = $this->extractBudget($normalized)) {
            return response()->json($this->handleBudgetIntent($budget, $customerName));
        }

        // 7. Check for Paket Hemat / Murah / Nasi
        if ($this->hasWordKeywords($normalized, ['paket murah', 'paket hemat', 'hemat', 'murah', 'nasi gurih', 'nasi putih', 'kantong', 'meriah', 'kenyang'])) {
            return response()->json($this->handlePaketIntent($normalized));
        }

        // 8. Check for Best Seller / Paling Best recommendations
        if ($this->hasWordKeywords($normalized, ['best', 'best seller', 'bestseller', 'paling best', 'laris', 'paling laris', 'rekomendasi', 'rekomended', 'paling enak', 'andalan', 'favorit', 'spesial', 'signature', 'top', 'terbaik', 'populer', 'juara', 'enak'])) {
            return response()->json($this->handleBestSellerIntent($customerName, $tableNumber));
        }

        // 9. Check for Greetings / Sapaan
        if ($this->hasWordKeywords($normalized, ['halo', 'hai', 'hello', 'pagi', 'siang', 'sore', 'malam', 'assalamu', 'tes', 'ping', 'bantuan', 'menu'])) {
            return response()->json($this->handleGreetingIntent($customerName, $tableNumber));
        }

        // 10. Fallback: Search menu names in database with LIKE query
        $rawClean = trim($message);
        $searchResults = Menu::where('is_available', true)
            ->where(function ($q) use ($rawClean) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$rawClean}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$rawClean}%"]);
            })
            ->take(4)
            ->get();

        if ($searchResults->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'reply' => "Berikut menu yang sesuai dengan pencarian *\"{$message}\"* dari dapur Depot Be Ba Lung:",
                'items' => $this->formatMenuItems($searchResults, "Pilihan Menu"),
                'quick_replies' => $this->getDefaultQuickReplies(),
            ]);
        }

        // Fallback default
        return response()->json([
            'success' => true,
            'reply' => "Wah, Chef siap bantu carikan hidangan paling nikmat! Kak {$customerName} bisa pilih rekomendasi menu paling best, olahan sate kambing empuk, kuah gulai/tongseng gurih, atau minuman segar di bawah ini ya: 👇",
            'items' => $this->getTopDatabaseMenus(3),
            'quick_replies' => $this->getDefaultQuickReplies(),
        ]);
    }

    /**
     * Get live recommendations endpoint for quick chips.
     */
    public function getRecommendations(Request $request): JsonResponse
    {
        $type = $request->query('type', 'best_seller');
        $customerName = $request->query('nama', 'Pelanggan');
        $tableNumber = $request->query('meja', '01');

        switch ($type) {
            case 'sate':
                return response()->json($this->handleSateIntent('sate'));
            case 'kuah':
                return response()->json($this->handleKuahIntent('kuah'));
            case 'minuman':
                return response()->json($this->handleDrinkIntent('minuman'));
            case 'hemat':
                return response()->json($this->handlePaketIntent('hemat'));
            case 'combo':
                return response()->json($this->handleComboIntent($customerName));
            case 'best_seller':
            default:
                return response()->json($this->handleBestSellerIntent($customerName, $tableNumber));
        }
    }

    /**
     * Best Seller Intent Handler - queries top menus & orders data.
     */
    protected function handleBestSellerIntent(string $customerName, string $tableNumber): array
    {
        $bestMenus = $this->getTopDatabaseMenus(4);

        $reply = "🔥 **Rekomendasi Menu Paling BEST SELLER & Favorit Pelanggan Depot Be Ba Lung:**\n\n";
        $reply .= "1. **Sate Kambing Polos** — 100% daging kambing muda pilihan tanpa lemak, super empuk dan tidak bau prengus!\n";
        $reply .= "2. **Gulai / Tongseng Kambing** — Kuah santan kental berempah khas Jawa, disajikan panas nikmat banget.\n";
        $reply .= "3. **Es Jeruk & Teh Poci Gula Batu** — Minuman paling pas setelah menyantap olahan kambing.\n\n";
        $reply .= "Silakan klik **\"+ Tambah ke Pesanan\"** pada menu di bawah untuk langsung pesan ke Meja #{$tableNumber} ya Kak {$customerName}! ✨";

        return [
            'success' => true,
            'reply' => $reply,
            'items' => $bestMenus,
            'quick_replies' => [
                '🍲 Mau Menu Kuah Hangat',
                '🥩 Mau Sate Kambing',
                '🥤 Minuman Segar',
                '💰 Rekomendasi Budget Murah',
                '🍱 Paket Kombo Lengkap',
            ],
        ];
    }

    /**
     * Sate Intent Handler.
     */
    protected function handleSateIntent(string $query): array
    {
        $menus = Menu::where('is_available', true)
            ->whereRaw('LOWER(name) LIKE ?', ['%sate%'])
            ->orderBy('sort_order', 'asc')
            ->get();

        if ($menus->isEmpty()) {
            $menus = Menu::where('is_available', true)->take(3)->get();
        }

        $reply = "🍢 **Pilihan Olahan Sate Andalan Depot Be Ba Lung:**\n\n";
        $reply .= "• **Sate Kambing Polos (👑 Best)**: Daging murni empuk tanpa selipan lemak, bumbu kecap pedas manis.\n";
        $reply .= "• **Sate Kambing Campur**: Perpaduan daging empuk & lemak gurih yang lumer saat dibakar.\n";
        $reply .= "• **Sate Ayam**: Pilihan daging ayam empuk gurih dengan bumbu kacang istimewa.";

        return [
            'success' => true,
            'reply' => $reply,
            'items' => $this->formatMenuItems($menus, "Sate Rekomendasi"),
            'quick_replies' => [
                '🍚 Tambah Nasi Gurih',
                '🍲 Mau Tambah Gulai / Sop',
                '🥤 Mau Minuman Pendamping',
                '🔥 Menu Paling Best Seller',
            ],
        ];
    }

    /**
     * Kuah / Sup / Gulai / Tongseng Intent Handler.
     */
    protected function handleKuahIntent(string $query): array
    {
        $menus = Menu::where('is_available', true)
            ->where(function ($q) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%gulai%'])
                  ->orWhereRaw('LOWER(name) LIKE ?', ['%tongseng%'])
                  ->orWhereRaw('LOWER(name) LIKE ?', ['%sop%']);
            })
            ->orderBy('sort_order', 'asc')
            ->get();

        $reply = "🍲 **Menu Kuah Hangat & Berempah Nikmat:**\n\n";
        $reply .= "• **Gulai Kambing**: Kuah kuning pekat berempah otentik dengan potongan daging lembut.\n";
        $reply .= "• **Tongseng Kambing**: Sensasi gurih, manis, ada sayur kol renyah dan tomat segar.\n";
        $reply .= "• **Sop Kambing**: Kuah kaldu bening yang segar dengan aroma rempah menghangatkan tubuh!";

        return [
            'success' => true,
            'reply' => $reply,
            'items' => $this->formatMenuItems($menus, "Kuah Hangat Spesial"),
            'quick_replies' => [
                '🥩 Mau Sate Kambing Polos',
                '🍚 Tambah Nasi Hangat',
                '🫖 Pesan Teh Poci Gula Batu',
                '🔥 Menu Best Seller Lainnya',
            ],
        ];
    }

    /**
     * Minuman Intent Handler.
     */
    protected function handleDrinkIntent(string $query): array
    {
        $menus = Menu::where('is_available', true)
            ->whereHas('category', function ($q) {
                $q->where('slug', 'minuman');
            })
            ->orderBy('price', 'desc')
            ->get();

        $reply = "🥤 **Pilihan Minuman Segar & Tradisional Pendamping Santap:**\n\n";
        $reply .= "• **Teh Poci Gula Batu (⭐ Favorit)**: Teh melati harum khas disajikan di poci tanah liat hangat.\n";
        $reply .= "• **Es Jeruk Segar**: Perasan jeruk asli kaya vitamin C, ampuh netralisir lemak sate.\n";
        $reply .= "• **Es Teh Manis / Tawar**: Segar dingin nikmat untuk santap santai.";

        return [
            'success' => true,
            'reply' => $reply,
            'items' => $this->formatMenuItems($menus, "Minuman Pilihan"),
            'quick_replies' => [
                '🔥 Menu Paling Best',
                '🍢 Sate Kambing Polos',
                '🍲 Gulai Kambing',
                '🍱 Paket Kombo Puas',
            ],
        ];
    }

    /**
     * Paket Hemat / Murah Intent Handler.
     */
    protected function handlePaketIntent(string $query): array
    {
        $menus = Menu::where('is_available', true)
            ->where(function ($q) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%paket%'])
                  ->orWhereRaw('LOWER(name) LIKE ?', ['%nasi%'])
                  ->orWhere('price', '<=', 25000);
            })
            ->orderBy('price', 'asc')
            ->take(5)
            ->get();

        $reply = "💰 **Pilihan Menu Hemat & Mengenyangkan:**\n\n";
        $reply .= "• **Paket Murah (Hanya Rp 22.000)**: Sudah lengkap dan ramah di kantong!\n";
        $reply .= "• **Nasi Gurih / Nasi Putih**: Mulai Rp 6.000, porsi pas bikin kenyang.\n";
        $reply .= "• **Sate Ayam**: Hanya Rp 20.000 dapat sate ayam empuk bumbu kacang.";

        return [
            'success' => true,
            'reply' => $reply,
            'items' => $this->formatMenuItems($menus, "Hemat & Nikmat"),
            'quick_replies' => [
                '🔥 Menu Paling Best Seller',
                '🥤 Minuman Segar',
                '🥩 Sate Kambing Polos',
            ],
        ];
    }

    /**
     * Budget Filter Intent Handler.
     */
    protected function handleBudgetIntent(int $budget, string $customerName): array
    {
        $formattedBudget = 'Rp ' . number_format($budget, 0, ',', '.');
        
        $menus = Menu::where('is_available', true)
            ->where('price', '<=', $budget)
            ->orderBy('price', 'desc')
            ->take(5)
            ->get();

        if ($menus->isEmpty()) {
            return [
                'success' => true,
                'reply' => "Untuk budget {$formattedBudget}, saat ini menu termurah kami mulai dari **Rp 2.000** (Teh/Air) dan Paket Makan mulai **Rp 22.000** (Paket Murah). Mau Chef tampilkan menu hemat terbaik?",
                'items' => $this->getTopDatabaseMenus(3),
                'quick_replies' => ['💰 Lihat Paket Murah', '🔥 Menu Best Seller', '🥤 Minuman Segar'],
            ];
        }

        $reply = "💵 **Menu Depot Be Ba Lung dengan harga di bawah {$formattedBudget}:**\n\n";
        $reply .= "Berikut menu pilihan yang pas dengan budget Kak {$customerName}:";

        return [
            'success' => true,
            'reply' => $reply,
            'items' => $this->formatMenuItems($menus, "Sesuai Budget"),
            'quick_replies' => [
                '🔥 Rekomendasi Menu Paling Best',
                '🍱 Paket Kombo Lengkap',
                '🍢 Sate Kambing',
            ],
        ];
    }

    /**
     * Combo / Set Meal Intent Handler.
     */
    protected function handleComboIntent(string $customerName): array
    {
        $sate = Menu::where('name', 'LIKE', '%Sate Kambing%')->first();
        $gulai = Menu::where('name', 'LIKE', '%Gulai%')->orWhere('name', 'LIKE', '%Tongseng%')->first();
        $nasi = Menu::where('name', 'LIKE', '%Nasi Gurih%')->orWhere('name', 'LIKE', '%Nasi Putih%')->first();
        $minum = Menu::where('name', 'LIKE', '%Jeruk%')->orWhere('name', 'LIKE', '%Poci%')->first();

        $items = collect([$sate, $gulai, $nasi, $minum])->filter();

        $reply = "👑 **Paket Kombo Juara (Rekomendasi Chef Be Ba Lung):**\n\n";
        $reply .= "Kombinasi paling mantap yang disukai 9 dari 10 pengunjung:\n";
        $reply .= "1. **Sate Kambing Polos** (Daging bakar empuk bumbu rempah)\n";
        $reply .= "2. **Gulai / Tongseng Kambing** (Kuah gurih kaya rempah)\n";
        $reply .= "3. **Nasi Gurih** (Nasi santan bertabur bawang goreng)\n";
        $reply .= "4. **Es Jeruk / Teh Poci** (Pelepas dahaga segar)\n\n";
        $reply .= "Kak {$customerName} bisa langsung klik **\"+ Tambah ke Pesanan\"** di bawah untuk memasukkan menu kombo ke keranjang!";

        return [
            'success' => true,
            'reply' => $reply,
            'items' => $this->formatMenuItems($items, "Paket Juara"),
            'quick_replies' => [
                '🔥 Lihat Best Seller Lainnya',
                '💰 Menu Budget Hemat',
                '📍 Info Alamat & Jam Buka',
            ],
        ];
    }

    /**
     * Restaurant Info Intent.
     */
    protected function handleRestaurantInfoIntent(): array
    {
        $reply = "📍 **Informasi Depot Sate & Gulai Be Ba Lung:**\n\n";
        $reply .= "🏠 **Alamat:** Jl. Supriyadi No. 40, Sukapura, Purwokerto Wetan, Banyumas, Jawa Tengah 53111\n";
        $reply .= "⏰ **Jam Buka:** Setiap Hari (Senin - Minggu) Pukul 10.00 - 21.00 WIB\n";
        $reply .= "💳 **Metode Pembayaran:** Scan QRIS Otomatis & Kasir Tunai / EDC\n";
        $reply .= "📞 **Pemesanan / Reservasi:** +62 812-2591-1012\n\n";
        $reply .= "Ada menu yang ingin dipesan sekarang Kak?";

        return [
            'success' => true,
            'reply' => $reply,
            'items' => $this->getTopDatabaseMenus(3),
            'quick_replies' => [
                '🔥 Menu Paling Best',
                '🥩 Sate Kambing Polos',
                '🍲 Gulai Kambing',
                '🍱 Paket Kombo Juara',
            ],
        ];
    }

    /**
     * Greeting Intent.
     */
    protected function handleGreetingIntent(string $customerName, string $tableNumber): array
    {
        $reply = "Halo Kak {$customerName}! Selamat datang di **Depot Sate Be Ba Lung** (Meja #{$tableNumber})! 🐐✨\n\n";
        $reply .= "Saya **Chef Bebalung AI**, siap membantu memilihkan menu terbaik untuk Kakak:\n";
        $reply .= "• Cari rekomendasi menu paling best seller & terfavorit\n";
        $reply .= "• Rekomendasi sate kambing empuk tanpa bau prengus\n";
        $reply .= "• Kuah gulai / tongseng / sop rempah hangat\n";
        $reply .= "• Cari menu sesuai budget tertentu (misal: *budget 30 ribu*)\n\n";
        $reply .= "Mau coba menu andalan apa hari ini?";

        return [
            'success' => true,
            'reply' => $reply,
            'items' => $this->getTopDatabaseMenus(3),
            'quick_replies' => $this->getDefaultQuickReplies(),
        ];
    }

    /**
     * Get top database menus based on sales or predefined ranking.
     */
    protected function getTopDatabaseMenus(int $limit = 4): array
    {
        // Try to get real sales from order_items if available
        $topIds = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->take($limit)
            ->pluck('menu_id')
            ->toArray();

        if (!empty($topIds)) {
            $menus = Menu::whereIn('id', $topIds)->where('is_available', true)->get();
        } else {
            // Priority fallback list
            $priorityNames = ['Sate Kambing Polos', 'Gulai Kambing', 'Tongseng Kambing', 'Paket Murah', 'Es Jeruk', 'Teh Poci'];
            $menus = Menu::where('is_available', true)
                ->whereIn('name', $priorityNames)
                ->orderBy('sort_order', 'asc')
                ->take($limit)
                ->get();

            if ($menus->count() < $limit) {
                $menus = Menu::where('is_available', true)->orderBy('sort_order', 'asc')->take($limit)->get();
            }
        }

        return $this->formatMenuItems($menus, "👑 Best Seller");
    }

    /**
     * Helper to format Menu collection into chatbot items.
     */
    protected function formatMenuItems($menus, string $defaultBadge = "Pilihan"): array
    {
        $results = [];
        foreach ($menus as $menu) {
            if (!$menu) continue;
            
            $badge = $defaultBadge;
            $nameLower = strtolower($menu->name);
            if (str_contains($nameLower, 'polos')) $badge = "👑 Paling Best";
            elseif (str_contains($nameLower, 'gulai') || str_contains($nameLower, 'tongseng')) $badge = "🔥 Best Seller";
            elseif (str_contains($nameLower, 'paket')) $badge = "💰 Hemat Puas";
            elseif (str_contains($nameLower, 'jeruk') || str_contains($nameLower, 'poci')) $badge = "⭐ Favorit Segar";

            $results[] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => (float)$menu->price,
                'formatted_price' => $menu->formatted_price,
                'description' => $menu->description ?? 'Menu istimewa khas Depot Sate Be Ba Lung.',
                'image_url' => $menu->image_url,
                'category_name' => $menu->category->name ?? 'Menu',
                'badge' => $badge,
            ];
        }
        return $results;
    }

    /**
     * Check if query contains any of the given word keywords.
     */
    protected function hasWordKeywords(string $text, array $keywords): bool
    {
        foreach ($keywords as $kw) {
            if (str_contains($text, $kw)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Extract budget number from message (e.g. 20rb, 25k, 30000, 50 ribu, di bawah 25000).
     */
    protected function extractBudget(string $text): ?int
    {
        // Check for '25rb', '25k', '50 ribu'
        if (preg_match('/(\d+)\s*(rb|ribu|k)\b/i', $text, $matches)) {
            $val = (int)$matches[1];
            return $val * 1000;
        }

        // Check for 'Rp 25.000' or '25000'
        if (preg_match('/(?:rp\.?|budget|harga|dibawah|di\s*bawah|kurang\s*dari)?\s*(\d{1,3}(?:\.\d{3})+|\d{4,6})/i', $text, $matches)) {
            $num = (int)str_replace('.', '', $matches[1]);
            if ($num >= 1000 && $num <= 500000) {
                return $num;
            }
        }

        return null;
    }

    /**
     * Check if query contains any profanity, vulgar, or obscene words.
     */
    protected function containsProfanity(string $text): bool
    {
        $badWords = [
            // Kata kotor / umpatan Bahasa Indonesia & Daerah
            'anjing', 'anjir', 'anjay', 'asu', 'bajingan', 'bangsat', 'babi', 'kampret',
            'kontol', 'kntl', 'memek', 'mmk', 'pantek', 'puki', 'peli', 'itil', 'jembut',
            'ngentot', 'ngewe', 'titit', 'tetek', 'toket', 'lonte', 'perek', 'pelacur',
            'tai', 'taek', 'bego', 'goblok', 'tolol', 'idiot', 'peler', 'pepek', 'tempik',
            'jancuk', 'jancok', 'dancuk', 'cuk', 'celeng', 'bodoh', 'sialan', 'setan', 'iblis',
            // Bahasa Inggris
            'fuck', 'fucking', 'bitch', 'shit', 'asshole', 'bastard', 'cunt', 'dick', 'pussy', 'slut', 'whore', 'boobs', 'penis', 'vagina'
        ];

        $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($text));
        $words = explode(' ', $clean);

        foreach ($words as $w) {
            $w = trim($w);
            if (empty($w)) continue;
            if (in_array($w, $badWords)) {
                return true;
            }
        }

        // Regex pattern for variations like k.o.n.t.o.l or repeated characters
        foreach ($badWords as $bw) {
            if (strlen($bw) >= 4 && str_contains($clean, $bw)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sensor kata kotor dengan karakter asterisk (*).
     */
    protected function censorProfanity(string $text): string
    {
        $badWords = [
            'anjing', 'anjir', 'anjay', 'asu', 'bajingan', 'bangsat', 'babi', 'kampret',
            'kontol', 'kntl', 'memek', 'mmk', 'pantek', 'puki', 'peli', 'itil', 'jembut',
            'ngentot', 'ngewe', 'titit', 'tetek', 'toket', 'lonte', 'perek', 'pelacur',
            'tai', 'taek', 'bego', 'goblok', 'tolol', 'peler', 'pepek',
            'jancuk', 'jancok', 'dancuk', 'fuck', 'bitch', 'shit', 'cunt', 'dick', 'pussy'
        ];

        foreach ($badWords as $bw) {
            $len = strlen($bw);
            if ($len <= 2) {
                $replacement = str_repeat('*', $len);
            } else {
                $replacement = $bw[0] . str_repeat('*', $len - 2) . $bw[$len - 1];
            }
            $text = preg_replace('/\b' . preg_quote($bw, '/') . '\b/i', $replacement, $text);
        }

        return $text;
    }

    /**
     * Default quick suggestion chips.
     */
    protected function getDefaultQuickReplies(): array
    {
        return [
            '🔥 Menu Paling Best Seller',
            '🍢 Sate Kambing Empuk',
            '🍲 Gulai & Tongseng Rempah',
            '🥤 Minuman Segar',
            '💰 Menu Hemat / Budget',
            '🍱 Rekomendasi Paket Kombo',
        ];
    }
}
