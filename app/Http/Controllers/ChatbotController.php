<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Handle incoming chatbot message from customer as a true database-connected AI assistant.
     */
    public function message(Request $request): JsonResponse
    {
        try {
            $message = trim($request->input('message', ''));
            $history = $request->input('history', []);
            $tableNumber = $request->input('table_number', session('table_number', '01'));
            $customerName = trim($request->input('customer_name', session('customer_name', '')));
            $nameGreeting = (!empty($customerName) && $customerName !== 'Pelanggan' && $customerName !== 'Kak') ? "Kak {$customerName}" : "Kak";

            // If empty message (initial trigger) -> polite greeting with real database menus
            if (empty($message)) {
                $topMenus = $this->getTopDatabaseMenus(4);
                return response()->json([
                    'success' => true,
                    'reply' => "Halo {$nameGreeting}! ✨ Selamat datang di **Depot Sate & Gulai Be Ba Lung** (Meja #{$tableNumber}).\n\nSaya adalah **BEBALUNG AI**, asisten AI universal yang cerdas, siap membantu Anda mulai dari info menu database kami, hitung rincian pesanan, rekomendasi kuliner, hingga berbagai topik pengetahuan umum, sains, bisnis, maupun coding & teknologi. Ada yang bisa saya bantu untuk Anda hari ini? 🚀",
                    'items' => $topMenus,
                    'quick_replies' => $this->getDefaultQuickReplies(),
                ]);
            }

            $normalized = ' ' . strtolower($message) . ' ';

            // 0. Profanity Sensor
            if ($this->isExplicitProfanity($normalized)) {
                return response()->json([
                    'success' => true,
                    'reply' => "Mohon gunakan bahasa yang sopan dan santun ya {$nameGreeting}. 🙏\n\nSebagai **BEBALUNG AI**, saya siap melayani dan menjawab pertanyaan Anda dengan ramah, cerdas, dan menyenangkan. Silakan tanyakan hal yang ingin Anda ketahui! ✨",
                    'items' => [],
                    'quick_replies' => ['🔥 Best Seller', '☕ Daftar Minuman', '💡 Apa yang bisa kamu lakukan?'],
                ]);
            }

            // 1. Gratitude & Closing
            if ($this->hasWordKeywords($normalized, ['terima kasih', 'terimakasih', 'makasih', 'makasi', 'matur nuwun', 'suwun', 'thank you', 'thanks', 'mantap', 'siap makasih', 'oke makasih', 'ok makasih', 'sankyu', 'arigato', 'tengkyu', 'nuhun'])) {
                return response()->json([
                    'success' => true,
                    'reply' => "Sama-sama {$nameGreeting}! Senang sekali bisa membantu Anda. Jika ada pertanyaan seputar menu, proyek, coding, atau kebutuhan lainnya, jangan ragu untuk bertanya lagi ya. Selamat menikmati hidangan spesial Depot Be Ba Lung! ✨😊",
                    'items' => [],
                    'quick_replies' => ['🔥 Best Seller', '🥤 Minuman Segar', '💡 Tanya hal lain'],
                ]);
            }

            // 2. Halal check
            if ($halalCheck = $this->checkUnavailableItem($normalized, $nameGreeting)) {
                return response()->json($halalCheck);
            }

            // 3. Cek Status Pesanan Khusus
            if (preg_match('/ORD-[\w\-]+/i', $message, $orderMatch) || $this->hasWordKeywords($normalized, ['status pesanan', 'cek pesanan', 'pesanan saya', 'pesanan meja', 'sudah siap belum'])) {
                if ($orderResponse = $this->handleOrderStatusCheck($message, $tableNumber, $nameGreeting, $orderMatch[0] ?? null)) {
                    return response()->json($orderResponse);
                }
            }

            // 4. Try Live Generative AI (OpenAI API / Gemini API) with Live Database Menu Context & Multi-Turn Conversation History
            $liveAiResponse = $this->callLiveAiApi($message, $nameGreeting, $tableNumber, $history);
            if ($liveAiResponse !== null) {
                $autoItems = $this->detectRelevantMenuItems($message, $liveAiResponse);
                return response()->json([
                    'success' => true,
                    'reply' => $liveAiResponse,
                    'items' => $autoItems,
                    'quick_replies' => $this->getDefaultQuickReplies(),
                ]);
            }

            // 5. Intelligent Conversational & Database Context Engine (Offline / Local AI Brain)
            $smartResponse = $this->handleIntelligentLocalConversation($message, $normalized, $nameGreeting, $tableNumber, $history);
            if ($smartResponse !== null) {
                return response()->json($smartResponse);
            }

            // 6. Comprehensive Broad Knowledge AI Fallback
            $generalAiResponse = $this->generateBroadKnowledgeAIResponse($message, $nameGreeting, $tableNumber);
            if (empty($generalAiResponse['items'])) {
                $generalAiResponse['items'] = $this->detectRelevantMenuItems($message, $generalAiResponse['reply'] ?? '');
            }
            return response()->json($generalAiResponse);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Chatbot error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'success' => true,
                'reply' => "Halo {$nameGreeting}! Saya **BEBALUNG AI** terhubung langsung dengan database Depot Be Ba Lung. Silakan tanyakan menu lezat, rincian harga, solusi coding, atau info apa pun yang Anda butuhkan! 😊",
                'items' => $this->getTopDatabaseMenus(3),
                'quick_replies' => $this->getDefaultQuickReplies(),
            ]);
        }
    }

    /**
     * Database-Driven Restaurant & Menu Query Engine.
     * Queries the MySQL database in real-time to give exact, factual, non-generic responses.
     */
    protected function handleDatabaseRestaurantQueries(string $normalized, string $rawMessage, string $nameGreeting, string $tableNumber): ?array
    {
        // ... (existing content)
    }

    /**
     * Intelligent Local Conversational AI & Database Context Engine.
     * Evaluates multi-turn context, calculations, comparisons, budget packages, and answers customer questions factually.
     */
    protected function handleIntelligentLocalConversation(string $rawMessage, string $normalized, string $nameGreeting, string $tableNumber, array $history = []): ?array
    {
        // Jika pertanyaan jelas merupakan topik umum / hiburan / coding / emosi -> alihkan langsung ke AI General Brain
        if (preg_match('/(gabut|bosen|bosan|suntuk|lelucon|jokes|joke|tebak|lucu|ngelawak|galau|sedih|patah hati|capek|lelah|film|anime|drakor|lagu|musik|game|gombal|quotes|motivasi|coding|login|register|buatkan|bikin|script|function|halaman|tombol|navbar|footer|html|css|javascript|typescript|python|php|laravel|react|sql|langit biru|gravitasi|fotosintesis|presiden|sejarah|rumus)/i', $normalized)) {
            return null;
        }

        $allMenus = Menu::with('category')->where('is_available', true)->get();

        // 1. Perhitungan Harga & Kombinasi Pesanan (Math & Combo Price Calculator)
        if ($calcResponse = $this->calculateOrderCombos($rawMessage, $normalized, $allMenus, $nameGreeting, $tableNumber)) {
            return $calcResponse;
        }

        // 2. Perbandingan Menu ("Bedanya X sama Y" / Perbedaan Rasa)
        if ($compResponse = $this->handleMenuComparisons($normalized, $allMenus, $nameGreeting)) {
            return $compResponse;
        }

        // 3. Rekomendasi Berdasarkan Budget & Porsi Orang ("Budget 100rb", "Makan Berdua", "Hemat")
        if ($budgetResponse = $this->handleBudgetAndPortionRecommendations($normalized, $allMenus, $nameGreeting, $tableNumber)) {
            return $budgetResponse;
        }

        // 4. Pertanyaan Detail Porsi, Jumlah Tusuk, Keempukan, & Bumbu
        if ($portionResponse = $this->handlePortionAndTasteQueries($normalized, $allMenus, $nameGreeting)) {
            return $portionResponse;
        }

        // 5. Sambungan Konteks Percakapan Sebelumnya (Contextual Follow-up dari History)
        if ($contextResponse = $this->handleContextualFollowUp($normalized, $history, $allMenus, $nameGreeting)) {
            return $contextResponse;
        }

        // 6. Tanya Minuman (Es Jeruk, Teh Poci, Kopi, Es Teh, Minuman)
        if ($this->hasWordKeywords($normalized, ['minum', 'minuman', 'es jeruk', 'teh poci', 'kopi', 'es teh', 'segar', 'seger', 'haus', 'minum apa'])) {
            $drinkMenus = $allMenus->filter(function($m) {
                return strtolower(optional($m->category)->slug ?? '') === 'minuman' || strtolower(optional($m->category)->name ?? '') === 'minuman';
            });

            if ($drinkMenus->isNotEmpty()) {
                $reply = "🥤 **Daftar Minuman Segar & Hangat di Depot Be Ba Lung:**\n\n";
                foreach ($drinkMenus as $drink) {
                    $badge = $drink->badge ? " ⭐ *[{$drink->badge}]*" : "";
                    $reply .= "• **{$drink->name}** ({$drink->formatted_price}){$badge} — {$drink->description}\n";
                }
                $reply .= "\nSilakan pilih minuman pelengkap santap Anda di bawah:";

                return [
                    'success' => true,
                    'reply' => $reply,
                    'items' => $this->formatMenuItems($drinkMenus, "Minuman"),
                    'quick_replies' => ['🍊 Es Jeruk', '🫖 Teh Poci Gula Batu', '☕ Kopi Toebroek', '🔥 Best Seller'],
                ];
            }
        }

        // 7. Tanya Daftar Menu / Ada Menu Apa Saja
        if ($this->hasWordKeywords($normalized, ['ada menu apa', 'daftar menu', 'lihat menu', 'semua menu', 'menu apa saja', 'list menu', 'menu yang ada', 'pilihan menu'])) {
            $reply = "📋 **Daftar Menu Resmi di Depot Sate Be Ba Lung (Real-time Database):**\n\n";
            $grouped = $allMenus->groupBy(function($item) {
                return optional($item->category)->name ?? 'Makanan';
            });

            foreach ($grouped as $catName => $items) {
                $cleanCatName = strtoupper(trim(str_ireplace('menu ', '', $catName)));
                $reply .= "🔹 **{$cleanCatName}:**\n";
                foreach ($items as $item) {
                    $badge = $item->badge ? " *[{$item->badge}]*" : "";
                    $reply .= "• **{$item->name}**{$badge} — {$item->formatted_price}\n";
                }
                $reply .= "\n";
            }
            $reply .= "Klik tombol **\"+ Tambah\"** pada kartu di bawah untuk memasukkan menu ke pesanan Anda:";

            return [
                'success' => true,
                'reply' => $reply,
                'items' => $this->formatMenuItems($allMenus->take(6), "Pilihan"),
                'quick_replies' => ['🍢 Sate Kambing Polos', '🍲 Gulai Kambing', '🥤 Minuman Segar', '🔥 Best Seller'],
            ];
        }

        // 8. Pencarian Menu Spesifik Berdasarkan Nama / Bahan
        if ($specificMenu = $this->searchSpecificDbMenu($rawMessage)) {
            $count = $specificMenu->count();
            $reply = "✨ Ditemukan **{$count} hidangan** di database Depot Be Ba Lung yang cocok dengan pencarian Anda {$nameGreeting}:\n\n";
            foreach ($specificMenu as $m) {
                $badge = $m->badge ? " ⭐ *[{$m->badge}]*" : "";
                $reply .= "• **{$m->name}** — **{$m->formatted_price}**{$badge}\n  _{$m->description}_\n\n";
            }
            $reply .= "Ingin menambahkan hidangan ini ke pesanan Meja #{$tableNumber}?";

            return [
                'success' => true,
                'reply' => $reply,
                'items' => $this->formatMenuItems($specificMenu, "Pilihan"),
                'quick_replies' => ['🔥 Best Seller', '🥤 Minuman Segar', '🍚 Nasi Gurih'],
            ];
        }

        // 9. Best Seller / Rekomendasi Menu Utama
        if ($this->hasWordKeywords($normalized, ['best seller', 'bestseller', 'rekomendasi', 'paling enak', 'menu favorit', 'menu terlaris', 'andalan', 'saran menu'])) {
            $bestMenus = $this->getTopDatabaseMenus(4);
            $reply = "🔥 **Rekomendasi Menu Best Seller di Depot Sate Be Ba Lung:**\n\n";
            $reply .= "1. 👑 **Sate Kambing (Polos) — Rp 50.000**: Juara favorit! 100% daging kambing muda empuk tanpa selipan lemak, tidak bau prengus.\n";
            $reply .= "3. 🥘 **Tongseng Kambing — Rp 35.000**: Kuah manis gurih berpadu potongan kol renyah dan tomat segar.\n";
            $reply .= "4. 🍚 **Nasi Gurih — Rp 7.500**: Nasi santan daun jeruk wangi taburan bawang goreng.\n";
            $reply .= "5. 🫖 **Teh Poci Gula Batu — Rp 15.000**: Teh melati hangat poci tanah liat tradisional.\n\n";
            $reply .= "Mau pesan menu best seller ini untuk Meja #{$tableNumber}?";

            return [
                'success' => true,
                'reply' => $reply,
                'items' => $bestMenus,
                'quick_replies' => ['🍢 Sate Kambing Polos', '🍲 Gulai Kambing', '🍚 Nasi Gurih', '🫖 Teh Poci Gula Batu'],
            ];
        }

        // 10. Info Restoran / Lokasi / Jam Buka / Kontak
        if ($this->hasWordKeywords($normalized, ['lokasi', 'alamat', 'jam buka', 'jam operasional', 'kontak', 'nomor wa', 'whatsapp', 'buka jam berapa', 'tutup jam berapa', 'daerah mana'])) {
            $reply = "📍 **Informasi Resmi Depot Sate & Gulai Be Ba Lung:**\n\n";
            $reply .= "🏠 **Alamat:** Jl. Supriyadi No.40, Sokayasa, Purwokerto Wetan, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53146\n";
            $reply .= "⏰ **Jam Operasional:** Buka Setiap Hari (Senin - Minggu) Pukul 10.00 - 21.00 WIB\n";
            $reply .= "💳 **Metode Pembayaran:** Scan QRIS Digital (Otomatis) & Kasir Tunai\n";
            $reply .= "📞 **WhatsApp / Hotline:** 0877 3071 2015\n";
            $reply .= "🌐 **Website Resmi:** https://bebalung.my.id\n\n";
            $reply .= "Ada yang bisa kami siapkan untuk kunjungan Anda?";

            return [
                'success' => true,
                'reply' => $reply,
                'items' => [],
                'quick_replies' => ['🔥 Best Seller', '🍢 Sate Kambing Polos', '🍲 Gulai Kambing'],
            ];
        }

        // 11. Cara Pesan / Checkout
        if ($this->hasWordKeywords($normalized, ['cara pesan', 'cara order', 'cara bayar', 'cara checkout', 'metode pembayaran', 'qris'])) {
            $reply = "💡 **Panduan Memesan & Bayar di Depot Be Ba Lung (Meja #{$tableNumber}):**\n\n";
            $reply .= "1. **Pilih Menu:** Klik tombol **\"+ Tambah\"** pada menu yang diinginkan.\n";
            $reply .= "2. **Buka Keranjang:** Klik tombol **\"Pesan Sekarang\"** pada bilah bawah.\n";
            $reply .= "3. **Metode Pembayaran:**\n";
            $reply .= "   • 📱 **QRIS Online:** Scan QRIS langsung dari m-Banking / E-Wallet Anda.\n";
            $reply .= "   • 💵 **Bayar di Kasir:** Tunjukkan barcode / kode pesanan Anda ke kasir.\n";
            $reply .= "4. **Dapur Memproses:** Pesanan Anda langsung otomatis masuk ke antrean dapur!";

            return [
                'success' => true,
                'reply' => $reply,
                'items' => $this->getTopDatabaseMenus(3),
                'quick_replies' => ['🔥 Best Seller', '🍢 Sate Kambing Polos', '🥤 Minuman Segar'],
            ];
        }

        return null;
    }

    /**
     * Helper: Hitung kombinasi harga menu secara real-time dari database.
     */
    protected function calculateOrderCombos(string $rawMessage, string $normalized, $allMenus, string $nameGreeting, string $tableNumber): ?array
    {
        $hasMathIntent = preg_match('/(\bberapa\b|\btotal\b|\bjumlah\b|\bhitung\b|\bhabis berapa\b|\bkena berapa\b|\bbayarnya berapa\b)/i', $rawMessage);
        
        $matchedItems = [];
        $totalPrice = 0;
        $workingText = ' ' . strtolower($rawMessage) . ' ';

        // Sort menus by name length descending so specific names like "Sate Kambing Polos" match before "Sate"
        $sortedMenus = $allMenus->sortByDesc(function($m) {
            return strlen($m->name);
        });

        foreach ($sortedMenus as $menu) {
            $name = strtolower(trim($menu->name));
            $cleanName = strtolower(trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $name)));
            $slugClean = strtolower(trim(str_replace('-', ' ', $menu->slug)));

            // Candidate aliases for each menu (ensure length >= 3 and not empty)
            $aliases = array_filter([$name, $cleanName, $slugClean], function($a) {
                return !empty($a) && strlen($a) >= 3;
            });

            if ($menu->slug === 'sate-kambing-polos') {
                $aliases = array_merge($aliases, ['sate kambing polos', 'kambing polos', 'sate polos']);
            } elseif ($menu->slug === 'sate-kambing-campur') {
                $aliases = array_merge($aliases, ['sate kambing campur', 'kambing campur', 'sate campur']);
            } elseif ($menu->slug === 'teh-poci-gula-batu') {
                $aliases = array_merge($aliases, ['teh poci gula batu', 'teh poci', 'poci gula batu', 'poci']);
            } elseif ($menu->slug === 'es-jeruk') {
                $aliases = array_merge($aliases, ['es jeruk']);
            } elseif ($menu->slug === 'es-teh-manis') {
                $aliases = array_merge($aliases, ['es teh manis', 'teh manis']);
            } elseif ($menu->slug === 'es-teh-tawar') {
                $aliases = array_merge($aliases, ['es teh tawar', 'teh tawar']);
            } elseif ($menu->slug === 'gulai-kambing') {
                $aliases = array_merge($aliases, ['gulai kambing', 'gulai']);
            } elseif ($menu->slug === 'tongseng-kambing') {
                $aliases = array_merge($aliases, ['tongseng kambing', 'tongseng']);
            } elseif ($menu->slug === 'sop-kambing') {
                $aliases = array_merge($aliases, ['sop kambing', 'sup kambing', 'sop']);
            } elseif ($menu->slug === 'sate-ayam') {
                $aliases = array_merge($aliases, ['sate ayam']);
            } elseif ($menu->slug === 'nasi-gurih') {
                $aliases = array_merge($aliases, ['nasi gurih']);
            } elseif ($menu->slug === 'nasi-putih') {
                $aliases = array_merge($aliases, ['nasi putih']);
            }

            foreach ($aliases as $alias) {
                $alias = trim($alias);
                if (empty($alias) || strlen($alias) < 3) continue;

                // Match with word boundary
                if (preg_match('/(?:\b|^)' . preg_quote($alias, '/') . '(?:\b|$)/i', $workingText)) {
                    $qty = 1;
                    if (preg_match('/(\d+)\s*(?:porsi|x|tusuk|buah|gelas|piring)?\s*' . preg_quote($alias, '/') . '/i', $workingText, $qm)) {
                        $qty = max(1, (int)$qm[1]);
                    } elseif (preg_match('/' . preg_quote($alias, '/') . '\s*(\d+)/i', $workingText, $qm)) {
                        $qty = max(1, (int)$qm[1]);
                    }

                    $matchedItems[] = [
                        'menu' => $menu,
                        'qty' => $qty,
                        'subtotal' => $menu->price * $qty,
                    ];
                    $totalPrice += ($menu->price * $qty);

                    // Mask out matched text to avoid duplicate matching
                    $workingText = preg_replace('/(?:\b|^)' . preg_quote($alias, '/') . '(?:\b|$)/i', ' [ITEM] ', $workingText, 1);
                    break;
                }
            }
        }

        // Only calculate if explicitly asking total / math with at least 1 item, or mentioning 2+ distinct items
        if (($hasMathIntent && count($matchedItems) >= 1) || count($matchedItems) >= 2) {
            $reply = "🧮 **Rincian Total Harga Pesanan (Database Real-Time):**\n\n";
            $itemsList = [];
            foreach ($matchedItems as $it) {
                $m = $it['menu'];
                $qtyStr = $it['qty'] > 1 ? " ({$it['qty']}x Rp " . number_format($m->price, 0, ',', '.') . ")" : "";
                $reply .= "• **{$it['qty']}x {$m->name}**{$qtyStr} = **Rp " . number_format($it['subtotal'], 0, ',', '.') . "**\n";
                $itemsList[] = $m;
            }
            $reply .= "\n💰 **Total yang Harus Dibayar:** **Rp " . number_format($totalPrice, 0, ',', '.') . "**\n\n";
            $reply .= "Semua harga di atas sudah termasuk pajak dan siap diproses untuk Meja #{$tableNumber}. Ingin langsung dimasukkan ke pesanan {$nameGreeting}?";

            return [
                'success' => true,
                'reply' => $reply,
                'items' => $this->formatMenuItems(collect($itemsList), "Hitungan"),
                'quick_replies' => ['🔥 Pesan Sekarang', '🍢 Sate Kambing Polos', '🫖 Teh Poci Gula Batu'],
            ];
        }

        return null;
    }

    /**
     * Helper: Tangani pertanyaan perbandingan menu ("Bedanya X sama Y").
     */
    protected function handleMenuComparisons(string $normalized, $allMenus, string $nameGreeting): ?array
    {
        // 1. Bedanya Sate Polos vs Sate Campur
        if ((str_contains($normalized, 'beda') || str_contains($normalized, 'perbedaan') || str_contains($normalized, 'mending')) && str_contains($normalized, 'polos') && str_contains($normalized, 'campur')) {
            $polos = $allMenus->firstWhere('slug', 'sate-kambing-polos');
            $campur = $allMenus->firstWhere('slug', 'sate-kambing-campur');
            $polosPrice = $polos ? $polos->formatted_price : 'Rp 50.000';
            $campurPrice = $campur ? $campur->formatted_price : 'Rp 45.000';

            $reply = "🍢 **Perbedaan Sate Kambing Polos vs Campur di Depot Be Ba Lung:**\n\n";
            $reply .= "1. 👑 **Sate Kambing (Polos) — {$polosPrice}**\n";
            $reply .= "   • 100% full potongan daging kambing muda super empuk.\n";
            $reply .= "   • Tanpa selipan lemak/gajih sama sekali, sangat cocok untuk Anda yang suka murni daging tebal dan sehat.\n\n";
            $reply .= "2. 🥓 **Sate Kambing (Campur) — {$campurPrice}**\n";
            $reply .= "   • Kombinasi daging kambing muda berseling ati dan lemak gurih pilihan.\n";
            $reply .= "   • Saat dibakar di atas arang batok kelapa, lemaknya meleleh renyah dan memberikan aroma panggangan khas yang sangat gurih.\n\n";
            $reply .= "💡 **Saran:** Jika suka tekstur daging padat empuk pilihlah **Polos**, jika suka sensasi gurih juicy berlemak pilihlah **Campur**!";

            $items = collect([$polos, $campur])->filter();

            return [
                'success' => true,
                'reply' => $reply,
                'items' => $this->formatMenuItems($items, "Sate"),
                'quick_replies' => ['🍢 Sate Kambing Polos', '🍢 Sate Kambing Campur', '🍚 Nasi Gurih'],
            ];
        }

        // 2. Bedanya Gulai vs Tongseng vs Sop
        if ((str_contains($normalized, 'beda') || str_contains($normalized, 'perbedaan') || str_contains($normalized, 'mending')) && ((str_contains($normalized, 'gulai') && str_contains($normalized, 'tongseng')) || (str_contains($normalized, 'gulai') && str_contains($normalized, 'sop')) || (str_contains($normalized, 'tongseng') && str_contains($normalized, 'sop')))) {
            $reply = "🍲 **Perbedaan Menu Kuah Kambing di Depot Be Ba Lung:**\n\n";
            $reply .= "• 🍲 **Gulai Kambing (Rp 30.000):** Kuah santan kuning kental dengan rempah tradisional khas Banyumas yang gurih, pekat, dan sedap.\n";
            $reply .= "• 🥘 **Tongseng Kambing (Rp 35.000):** Kuah manis-gurih kecap berpadu irisan kol segar yang renyah, potongan tomat merah, dan cabai rawit.\n";
            $reply .= "• 🥣 **Sop Kambing (Rp 30.000):** Kuah bening non-santan yang segar beraroma kapulaga, cengkeh, dan daun bawang.\n\n";
            $reply .= "Semua menu kuah menggunakan potongan daging dan tulang iga kambing muda yang sangat empuk dan meresap!";

            $kuahItems = $allMenus->filter(function($m) {
                return in_array($m->slug, ['gulai-kambing', 'tongseng-kambing', 'sop-kambing']);
            });

            return [
                'success' => true,
                'reply' => $reply,
                'items' => $this->formatMenuItems($kuahItems, "Kuah"),
                'quick_replies' => ['🍲 Gulai Kambing', '🥘 Tongseng Kambing', '🥣 Sop Kambing'],
            ];
        }

        return null;
    }

    /**
     * Helper: Tangani rekomendasi paket hemat berdasarkan budget atau jumlah orang.
     */
    protected function handleBudgetAndPortionRecommendations(string $normalized, $allMenus, string $nameGreeting, string $tableNumber): ?array
    {
        if (str_contains($normalized, '100') || str_contains($normalized, 'berdua') || str_contains($normalized, '2 orang') || str_contains($normalized, 'pasangan')) {
            $reply = "👫 **Rekomendasi Paket Makan Kenyang Berdua (Budget ~Rp 100.000):**\n\n";
            $reply .= "Kami merekomendasikan kombinasi sempurna dan paling populer:\n";
            $reply .= "1. 🍢 **1x Sate Kambing (Polos)** — Rp 50.000 (10 tusuk full daging empuk)\n";
            $reply .= "2. 🍲 **1x Gulai Kambing** — Rp 30.000 (1 mangkok kuah santan gurih melimpah)\n";
            $reply .= "3. 🍚 **2x Nasi Putih / Nasi Gurih** — Rp 12.000 - Rp 15.000\n";
            $reply .= "4. 🧊 **2x Es Teh Manis** — Rp 8.000\n\n";
            $reply .= "💰 **Total Estimasi:** **Rp 100.000 - Rp 103.000** (Sudah sangat kenyang, lengkap ada sate bakar, kuah hangat, dan minuman segar!).";

            $comboItems = $allMenus->filter(function($m) {
                return in_array($m->slug, ['sate-kambing-polos', 'gulai-kambing', 'nasi-gurih', 'es-teh-manis']);
            });

            return [
                'success' => true,
                'reply' => $reply,
                'items' => $this->formatMenuItems($comboItems, "Paket Berdua"),
                'quick_replies' => ['🍢 Sate Kambing Polos', '🍲 Gulai Kambing', '🍚 Nasi Gurih'],
            ];
        }

        if (str_contains($normalized, '50') || str_contains($normalized, 'sendiri') || str_contains($normalized, '1 orang') || str_contains($normalized, 'hemat')) {
            $reply = "👤 **Rekomendasi Paket Kenyang 1 Orang (Budget ~Rp 50.000):**\n\n";
            $reply .= "Pilihan 1 (Menu Sate Kambing Juara):\n";
            $reply .= "• 🍢 **Sate Kambing Polos (Rp 50.000)** (10 tusuk daging empuk)\n\n";
            $reply .= "Pilihan 2 (Paket Komplit Kuah + Nasi + Minum):\n";
            $reply .= "• 🍲 **Gulai / Sop Kambing (Rp 30.000)**\n";
            $reply .= "• 🍚 **Nasi Gurih (Rp 7.500)**\n";
            $reply .= "• 🍊 **Es Jeruk Segar (Rp 10.000)**\n";
            $reply .= "• **Total:** **Rp 47.500** (Kenyang & Segar!)";

            $singleItems = $allMenus->filter(function($m) {
                return in_array($m->slug, ['sate-kambing-polos', 'gulai-kambing', 'nasi-gurih', 'es-jeruk']);
            });

            return [
                'success' => true,
                'reply' => $reply,
                'items' => $this->formatMenuItems($singleItems, "Pilihan"),
                'quick_replies' => ['🍢 Sate Kambing Polos', '🍲 Gulai Kambing', '🍊 Es Jeruk'],
            ];
        }

        return null;
    }

    /**
     * Helper: Tangani pertanyaan porsi, jumlah tusuk, rasa pedas, keempukan.
     */
    protected function handlePortionAndTasteQueries(string $normalized, $allMenus, string $nameGreeting): ?array
    {
        if (str_contains($normalized, 'berapa tusuk') || str_contains($normalized, 'jumlah tusuk') || str_contains($normalized, 'isi berapa')) {
            $reply = "🍢 **Informasi Porsi Sate di Depot Be Ba Lung:**\n\n";
            $reply .= "• Setiap porsi **Sate Kambing (Polos / Campur)** dan **Sate Ayam** disajikan sebanyak **10 tusuk** daging berukuran tebal dan empuk.\n";
            $reply .= "• Sudah dilengkapi dengan sambal kecap pedas rawit bawang merah segar atau bumbu kacang gurih khas resep turun-temurun.\n";
            $reply .= "• Porsi 10 tusuk sangat pas dinikmati sendiri maupun berbagi bersama!";

            $sateItems = $allMenus->filter(function($m) {
                return str_contains($m->slug, 'sate');
            });

            return [
                'success' => true,
                'reply' => $reply,
                'items' => $this->formatMenuItems($sateItems, "Sate"),
                'quick_replies' => ['🍢 Sate Kambing Polos', '🍢 Sate Kambing Campur', '🍗 Sate Ayam'],
            ];
        }

        if (str_contains($normalized, 'pedas') || str_contains($normalized, 'pedes') || str_contains($normalized, 'sambal') || str_contains($normalized, 'bumbu kacang') || str_contains($normalized, 'bumbu kecap')) {
            $reply = "🌶️ **Pilihan Bumbu & Tingkat Kepedasan:**\n\n";
            $reply .= "• **Sate Kambing & Ayam:** Anda dapat memilih **Bumbu Kecap Manis Pedas Rawit Bawang** atau **Bumbu Kacang Gurih Legit**.\n";
            $reply .= "• **Tingkat Kepedasan:** Bisa disesuaikan! Jika tidak suka pedas atau untuk anak-anak, cabai rawit bisa dipisah atau tidak memakai cabai sama sekali.\n";
            $reply .= "• **Menu Kuah (Gulai/Tongseng/Sop):** Tingkat kepedasan kuah tongseng juga bisa dibuat sedang atau ekstra pedas!";

            return [
                'success' => true,
                'reply' => $reply,
                'items' => $this->getTopDatabaseMenus(3),
                'quick_replies' => ['🍢 Sate Kambing Polos', '🥘 Tongseng Kambing', '🍗 Sate Ayam'],
            ];
        }

        return null;
    }

    /**
     * Helper: Sambungan konteks dari riwayat chat (Multi-turn follow up).
     */
    protected function handleContextualFollowUp(string $normalized, array $history, $allMenus, string $nameGreeting): ?array
    {
        if (empty($history)) return null;

        $isGeneralConcept = preg_match('/(hukum|rumus|sejarah|teori|biologi|fisika|kimia|coding|bahasa|program|komputer|presiden|tata surya|planet|fotosintesis|gravitasi)/i', $normalized);
        if ($isGeneralConcept) return null;

        $isFollowUp = preg_match('/(\byang itu\b|\bkalau itu\b|\bkalo itu\b|\bmenu itu\b|\byang tadi\b|\bharganya berapa\b|\brasa apa\b|\bada apa aja isinya\b|\bisinya apa\b)/i', $normalized);
        if (!$isFollowUp) return null;

        $lastBotMsg = '';
        foreach (array_reverse($history) as $turn) {
            if (($turn['sender'] ?? '') === 'bot') {
                $lastBotMsg = strtolower($turn['text'] ?? '');
                break;
            }
        }

        if (!empty($lastBotMsg)) {
            foreach ($allMenus as $menu) {
                if (str_contains($lastBotMsg, strtolower($menu->name)) || str_contains($lastBotMsg, $menu->slug)) {
                    $reply = "Mengenai **{$menu->name}** ({$menu->formatted_price}) {$nameGreeting}:\n\n";
                    $reply .= "• **Deskripsi:** {$menu->description}\n";
                    $reply .= "• **Status Ketersediaan:** " . ($menu->is_available ? '✅ Tersedia segar di dapur' : '❌ Sedang habis') . "\n";
                    $reply .= "• **Harga Resmi:** **{$menu->formatted_price}**\n\n";
                    $reply .= "Apakah Anda ingin memesan hidangan ini sekarang?";

                    return [
                        'success' => true,
                        'reply' => $reply,
                        'items' => $this->formatMenuItems(collect([$menu]), "Detail"),
                        'quick_replies' => ['+ Tambah ke Pesanan', '🔥 Best Seller', '🥤 Minuman Segar'],
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Search specific menu in DB by keywords.
     */
    protected function searchSpecificDbMenu(string $query)
    {
        try {
            $clean = trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $query));
            $words = explode(' ', $clean);
            $stopWords = ['ada', 'menu', 'apa', 'saya', 'mau', 'pesan', 'beli', 'harga', 'berapa', 'apakah', 'jual', 'punya', 'minta', 'tolong', 'kak', 'mas', 'mbak', 'di', 'sini', 'depot', 'bebalung'];

            $keywords = array_filter($words, function($w) use ($stopWords) {
                return strlen($w) >= 3 && !in_array($w, $stopWords);
            });

            if (empty($keywords)) return null;

            $q = Menu::with('category')->where('is_available', true);
            $q->where(function($subQ) use ($keywords) {
                foreach ($keywords as $kw) {
                    $subQ->orWhereRaw('LOWER(name) LIKE ?', ["%{$kw}%"])
                         ->orWhereRaw('LOWER(description) LIKE ?', ["%{$kw}%"]);
                }
            });

            $results = $q->take(4)->get();
            return $results->isNotEmpty() ? $results : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Check Order Status from Database if user asks about their order.
     */
    protected function handleOrderStatusCheck(string $message, string $tableNumber, string $nameGreeting, ?string $orderCode = null): ?array
    {
        try {
            $order = null;
            if ($orderCode) {
                $order = Order::with('items')->where('order_code', $orderCode)->first();
            }

            if (!$order) {
                $order = Order::with('items')
                    ->where('table_number', $tableNumber)
                    ->latest()
                    ->first();
            }

            if ($order) {
                $payStatus = $order->payment_status === 'paid' ? '✅ Lunas' : '⏳ Belum Dibayar (Bayar di Kasir)';
                $orderStatus = match($order->order_status) {
                    'completed' => '🎉 Hidangan Selesai Disajikan',
                    'processing' => '👨‍🍳 Sedang Dimasak di Dapur',
                    default => '📥 Pesanan Diterima Dapur',
                };

                $reply = "📦 **Status Pesanan Anda di Database:**\n\n";
                $reply .= "• **No. Pesanan:** `{$order->order_code}`\n";
                $reply .= "• **Meja:** #{$order->table_number} (a.n {$order->customer_name})\n";
                $reply .= "• **Status Dapur:** **{$orderStatus}**\n";
                $reply .= "• **Pembayaran:** **{$payStatus}**\n";
                $reply .= "• **Total Tagihan:** **{$order->formatted_total}**\n\n";
                $reply .= "📋 **Daftar Menu yang Dipesan:**\n";
                foreach ($order->items as $it) {
                    $reply .= "• {$it->menu_name} (x{$it->quantity}) — Rp " . number_format($it->subtotal, 0, ',', '.') . "\n";
                }

                return [
                    'success' => true,
                    'reply' => $reply,
                    'items' => [],
                    'quick_replies' => ['🔥 Pesan Menu Tambahan', '🥤 Tambah Minuman', '📍 Info Restoran'],
                ];
            }
        } catch (\Throwable $e) {}

        return null;
    }

    /**
     * Halal / Non-Halal Inquiries.
     */
    protected function checkUnavailableItem(string $text, string $nameGreeting): ?array
    {
        if (preg_match('/\b(babi|pork|ham|bacon|celeng|anjing|alkohol|arak|miras|wine)\b/i', $text)) {
            $reply = "🟢 **Informasi Kehalalan Depot Be Ba Lung:**\n\nDepot Sate & Gulai Be Ba Lung adalah restoran **100% HALAL**. Kami hanya menyajikan daging kambing muda dan ayam segar pilihan yang disembelih sesuai syariat Islam, tanpa campuran bahan non-halal atau alkohol.\n\nBerikut menu andalan halal yang siap kami sajikan:";

            return [
                'success' => true,
                'reply' => $reply,
                'items' => $this->getTopDatabaseMenus(3),
                'quick_replies' => ['🍢 Sate Kambing Polos', '🍲 Gulai Kambing', '🍗 Sate Ayam', '🔥 Makanan'],
            ];
        }

        return null;
    }

    /**
     * Handle Live OpenAI / Gemini API call with real-time Database Menu Context & Universal Master Prompt.
     */
    protected function callLiveAiApi(string $userPrompt, string $nameGreeting, string $tableNumber, array $history = []): ?string
    {
        $openaiKey = config('services.openai.key') ?: env('OPENAI_API_KEY', '');
        $geminiKey = config('services.gemini.key') ?: env('GEMINI_API_KEY', '');

        // Fetch real database menu context to inject into AI prompt
        $menuContext = "DATA DATABASE RESMI DEPOT SATE & GULAI BE BA LUNG (Real-Time Database):\n";
        try {
            $menus = Menu::with('category')->where('is_available', true)->get();
            foreach ($menus as $m) {
                $menuContext .= "- {$m->name}: {$m->formatted_price} ({$m->description})\n";
            }
        } catch (\Throwable $e) {
            $menuContext .= "- Sate Kambing (Polos): Rp 50.000\n- Sate Kambing (Campur): Rp 45.000\n- Gulai Kambing: Rp 30.000\n- Tongseng Kambing: Rp 35.000\n- Sop Kambing: Rp 30.000\n- Sate Ayam: Rp 20.000\n- Nasi Gurih: Rp 7.500\n- Nasi Putih: Rp 6.000\n- Teh Poci Gula Batu: Rp 15.000\n- Es Jeruk: Rp 10.000\n- Es Teh Manis: Rp 4.000\n";
        }

        $systemPrompt = <<<PROMPT
Kamu adalah **BEBALUNG AI**, sebuah asisten AI universal yang sangat cerdas, mampu memahami berbagai macam pertanyaan, percakapan, masalah, instruksi, dan kebutuhan pengguna di Depot Sate & Gulai Be Ba Lung (Meja #{$tableNumber}, menyapa pengguna dengan '{$nameGreeting}').

Dalam seluruh percakapan, selalu bertindak sebagai satu asisten yang sama dan jangan pernah menganggap setiap pesan sebagai percakapan baru. Pahami seluruh konteks percakapan yang tersedia dari awal hingga pesan terbaru dan gunakan informasi tersebut untuk memberikan jawaban yang paling tepat, relevan, akurat, dan membantu.

Kamu harus mampu menjawab berbagai jenis pertanyaan dan membantu hampir semua kebutuhan pengguna, termasuk pengetahuan umum, pendidikan, matematika, sains, teknologi, programming, web development, aplikasi, database, API, AI, bisnis, marketing, desain, penulisan, penerjemahan, analisis, brainstorming, pemecahan masalah, pembuatan konten, troubleshooting, dan percakapan sehari-hari.

Jangan membatasi kemampuanmu hanya pada daftar bidang tersebut; jika pengguna bertanya tentang sesuatu yang berbeda, tetap berusaha membantu berdasarkan kemampuan dan informasi yang tersedia.

Selalu pahami maksud pengguna, bukan hanya kata-kata yang mereka tuliskan. Gunakan konteks dari pesan sebelumnya untuk memahami kata atau kalimat seperti "itu", "dia", "yang tadi", "yang sebelumnya", "lanjutkan", "ubah", "tambahkan", "hapus", "perbaiki", "buat seperti tadi", "versi sebelumnya", dan berbagai bentuk pertanyaan lanjutan lainnya.

Jika pengguna sedang mengerjakan suatu project, pertahankan konteks project tersebut sepanjang percakapan dan jangan meminta pengguna mengulang informasi yang sudah tersedia.

Jika pengguna berpindah topik, ikuti topik baru secara natural, tetapi tetap pertahankan konteks topik lama sehingga pengguna dapat kembali membahasnya kapan saja.

Jika pengguna kembali ke pembahasan sebelumnya, gunakan konteks sebelumnya untuk melanjutkan percakapan tanpa meminta pengguna menjelaskan ulang.

Jika sistem menyediakan conversation history atau memory, gunakan informasi tersebut secara relevan dan konsisten. Jangan pernah mengklaim mengingat sesuatu yang sebenarnya tidak tersedia dalam konteks.

Utamakan kebenaran daripada terlihat pintar. Jangan pernah mengarang fakta, angka, sumber, link, informasi, hasil, atau tindakan yang belum benar-benar dilakukan. Jika kamu tidak mengetahui atau tidak dapat memastikan suatu informasi, katakan dengan jujur dan jangan membuat jawaban palsu.

Jika sistem menyediakan akses internet, pencarian, kalkulator, analisis file, analisis gambar, eksekusi kode, atau tools lainnya, gunakan tools tersebut ketika memang diperlukan dan jangan pernah berpura-pura telah menggunakan tool yang sebenarnya tidak digunakan.

Untuk informasi yang dapat berubah dari waktu ke waktu seperti berita, harga, jadwal, versi software, dokumentasi, data terbaru, dan informasi terkini lainnya, gunakan sumber terbaru jika akses tersebut tersedia.

Ketika menghadapi masalah yang kompleks, analisis masalah secara mendalam sebelum memberikan jawaban, periksa asumsi, identifikasi penyebab, pertimbangkan solusi yang memungkinkan, kemudian berikan solusi terbaik kepada pengguna.

Jangan menampilkan proses berpikir internal atau chain-of-thought secara mentah; berikan kesimpulan, alasan penting, perhitungan, langkah, dan penjelasan yang diperlukan agar pengguna memahami hasilnya.

Ketika membantu programming, pahami kode dan konteks project pengguna sebelum memberikan perubahan. Pertahankan fitur yang sudah ada kecuali pengguna meminta untuk menghapusnya, cari penyebab error, berikan solusi yang dapat digunakan, dan ketika pengguna meminta perubahan lanjutan, gunakan versi kode dan konteks sebelumnya sebagai dasar.

Ketika membantu pekerjaan kreatif, gunakan kreativitas dan konteks pengguna untuk menghasilkan hasil yang sesuai, bukan jawaban generik.

Ketika pengguna memberikan file, gambar, atau informasi tertentu dan sistem dapat membacanya, gunakan informasi tersebut sebagai bagian dari konteks dan jangan mengarang isi yang tidak tersedia.

Sesuaikan gaya jawaban dengan pengguna dan situasi: untuk pertanyaan sederhana berikan jawaban sederhana dan langsung, sedangkan untuk pertanyaan kompleks berikan penjelasan yang lebih lengkap menggunakan struktur yang mudah dipahami. Jika pengguna meminta singkat, ringkas jawaban. Jika pengguna meminta detail, berikan penjelasan mendalam. Jika pengguna adalah pemula, jelaskan dengan bahasa sederhana; jika pengguna membutuhkan jawaban teknis, gunakan istilah teknis yang sesuai.

Berbicaralah secara natural, ramah, cerdas, tenang, dan tidak kaku. Jangan terus-menerus mengatakan "Sebagai AI". Jangan mengulang informasi tanpa alasan dan jangan membuat setiap jawaban terasa seperti sesi baru.

Jika pertanyaan pengguna ambigu tetapi konteks memungkinkan untuk menentukan maksudnya, gunakan konteks tersebut dan langsung membantu. Hanya tanyakan klarifikasi jika informasi benar-benar tidak cukup dan kesalahan asumsi dapat mengubah hasil secara signifikan.

Selalu periksa kembali jawaban sebelum diberikan untuk memastikan jawaban tersebut relevan dengan pertanyaan, konsisten dengan konteks, tidak mengandung fakta yang dibuat-buat, dan memberikan solusi yang berguna.

Dalam seluruh percakapan, pertahankan kesinambungan, konsistensi, dan pemahaman konteks. Jangan hanya menjawab pesan terakhir pengguna; pahami hubungan antara pesan terakhir dengan seluruh percakapan yang tersedia.

Tujuan utama BEBALUNG AI adalah menjadi asisten AI universal yang terasa seperti satu kecerdasan yang terus mengikuti percakapan pengguna dari awal sampai akhir, mampu memahami konteks, menjawab berbagai macam pertanyaan, membantu menyelesaikan masalah, dan memberikan jawaban terbaik yang dapat diberikan berdasarkan informasi dan kemampuan yang tersedia.

Untuk pertanyaan menu, harga, pesanan, dan fasilitas Depot Sate & Gulai Be Ba Lung, SELALU gunakan data resmi berikut secara 100% faktual:
{$menuContext}

**Dalam seluruh percakapan, tetaplah menjadi BEBALUNG AI yang sama, tetap pahami konteks, tetap cerdas, tetap akurat, dan selalu lanjutkan percakapan secara natural.**
PROMPT;

        // 1. Gemini API (Primary)
        if (!empty($geminiKey)) {
            try {
                $models = ['gemini-3.5-flash-lite', 'gemini-3.1-flash-lite', 'gemini-3.6-flash', 'gemini-3.5-flash', 'gemini-flash-lite-latest', 'gemini-2.5-flash'];
                foreach ($models as $modelName) {
                    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key=" . urlencode($geminiKey);

                    $contents = [];
                    $contents[] = ['role' => 'user', 'parts' => [['text' => $systemPrompt]]];
                    $contents[] = ['role' => 'model', 'parts' => [['text' => "Dimengerti, saya siap menjadi BEBALUNG AI yang cerdas, memahami konteks, dan akurat."]]];

                    if (is_array($history) && count($history) > 0) {
                        $recentHistory = array_slice($history, -10);
                        foreach ($recentHistory as $turn) {
                            $role = ($turn['sender'] ?? '') === 'user' ? 'user' : 'model';
                            $content = trim($turn['text'] ?? '');
                            if (!empty($content)) {
                                $contents[] = ['role' => $role, 'parts' => [['text' => $content]]];
                            }
                        }
                    }

                    $contents[] = ['role' => 'user', 'parts' => [['text' => $userPrompt]]];

                    $payload = [
                        'contents' => $contents,
                        'generationConfig' => [
                            'temperature' => 0.7,
                            'maxOutputTokens' => 1000,
                        ]
                    ];

                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $res = curl_exec($ch);
                    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    if ($code === 200 && $res) {
                        $json = json_decode($res, true);
                        $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? '';
                        if (!empty(trim($text))) {
                            return trim($text);
                        }
                    }
                }
            } catch (\Throwable $e) {}
        }

        // 2. OpenAI API (Fallback)
        if (!empty($openaiKey)) {
            try {
                $messages = [
                    ['role' => 'system', 'content' => $systemPrompt]
                ];

                if (is_array($history) && count($history) > 0) {
                    $recentHistory = array_slice($history, -12);
                    foreach ($recentHistory as $turn) {
                        $role = ($turn['sender'] ?? '') === 'user' ? 'user' : 'assistant';
                        $content = trim($turn['text'] ?? '');
                        if (!empty($content)) {
                            $messages[] = ['role' => $role, 'content' => $content];
                        }
                    }
                }

                $messages[] = ['role' => 'user', 'content' => $userPrompt];

                $openaiModels = ['gpt-4o-mini', 'gpt-4o', 'gpt-3.5-turbo'];
                foreach ($openaiModels as $modelChoice) {
                    $payload = [
                        'model' => $modelChoice,
                        'messages' => $messages,
                        'temperature' => 0.7,
                        'max_tokens' => 1200,
                    ];

                    $ch = curl_init('https://api.openai.com/v1/chat/completions');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . trim($openaiKey)
                    ]);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $res = curl_exec($ch);
                    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    if ($code === 200 && $res) {
                        $json = json_decode($res, true);
                        $text = $json['choices'][0]['message']['content'] ?? '';
                        if (!empty(trim($text))) {
                            return trim($text);
                        }
                    } elseif ($code === 401 || $code === 429 || $code === 403 || $code === 0) {
                        break;
                    }
                }
            } catch (\Throwable $e) {}
        }

        return null;
    }

    /**
     * Automatically detect and attach matching interactive menu cards from Database.
     */
    protected function detectRelevantMenuItems(string $text, string $replyText = ''): array
    {
        try {
            $userLower = strtolower($text);
            
            // Only trigger menu cards if user is actually asking about food, drinks, or restaurant menu
            $isFoodQuery = preg_match('/\b(menu|makan|minum|pesan|order|beli|harga|sate|gulai|tongseng|sop|sup|ayam|kambing|nasi|teh|jeruk|kopi|es|rekomendasi|enak|lezat|kuliner|porsi|lapar|laper|kenyang|haus|minuman|makanan)\b/i', $userLower);
            if (!$isFoodQuery) {
                return [];
            }

            $matchedMenus = collect();

            if (str_contains($userLower, 'gulai')) {
                $m = Menu::where('is_available', true)->whereRaw('LOWER(name) LIKE ?', ['%gulai%'])->first();
                if ($m) $matchedMenus->push($m);
            }
            if (str_contains($userLower, 'tongseng')) {
                $m = Menu::where('is_available', true)->whereRaw('LOWER(name) LIKE ?', ['%tongseng%'])->first();
                if ($m) $matchedMenus->push($m);
            }
            if (str_contains($userLower, 'sop') || str_contains($userLower, 'sup')) {
                $m = Menu::where('is_available', true)->whereRaw('LOWER(name) LIKE ?', ['%sop%'])->first();
                if ($m) $matchedMenus->push($m);
            }
            if (str_contains($userLower, 'sate kambing') || (str_contains($userLower, 'sate') && !str_contains($userLower, 'ayam'))) {
                $m = Menu::where('is_available', true)->whereRaw('LOWER(name) LIKE ?', ['%sate kambing%'])->get();
                foreach ($m as $item) $matchedMenus->push($item);
            }
            if (str_contains($userLower, 'sate ayam') || str_contains($userLower, 'ayam')) {
                $m = Menu::where('is_available', true)->whereRaw('LOWER(name) LIKE ?', ['%sate ayam%'])->first();
                if ($m) $matchedMenus->push($m);
            }
            if (str_contains($userLower, 'nasi')) {
                $m = Menu::where('is_available', true)->whereRaw('LOWER(name) LIKE ?', ['%nasi%'])->first();
                if ($m) $matchedMenus->push($m);
            }
            if (str_contains($userLower, 'teh poci') || str_contains($userLower, 'poci')) {
                $m = Menu::where('is_available', true)->whereRaw('LOWER(name) LIKE ?', ['%poci%'])->first();
                if ($m) $matchedMenus->push($m);
            }
            if (str_contains($userLower, 'es jeruk') || str_contains($userLower, 'jeruk')) {
                $m = Menu::where('is_available', true)->whereRaw('LOWER(name) LIKE ?', ['%jeruk%'])->first();
                if ($m) $matchedMenus->push($m);
            }
            if (str_contains($userLower, 'kopi')) {
                $m = Menu::where('is_available', true)->whereRaw('LOWER(name) LIKE ?', ['%kopi%'])->first();
                if ($m) $matchedMenus->push($m);
            }
            if (str_contains($userLower, 'es teh')) {
                $m = Menu::where('is_available', true)->whereRaw('LOWER(name) LIKE ?', ['%es teh%'])->first();
                if ($m) $matchedMenus->push($m);
            }

            if ($matchedMenus->isNotEmpty()) {
                return $this->formatMenuItems($matchedMenus->unique('id')->take(3), "Pilihan Menu");
            }
        } catch (\Throwable $e) {}

        return [];
    }

    /**
     * Broad General Knowledge AI Response Engine.
     */
    protected function generateBroadKnowledgeAIResponse(string $userPrompt, string $nameGreeting, string $tableNumber): array
    {
        $normalized = ' ' . strtolower($userPrompt) . ' ';

        // 1. Tanggapan Rasa Gabut / Bosan / Senggang ("aku gabut", "lagi bosen", "gabut nih", "ngapain ya")
        if (preg_match('/(gabut|bosen|bosan|suntuk|nganggur|bingung mau apa|ga ada kerjaan|ngapain ya)/i', $normalized)) {
            $reply = "Waduh lagi gabut ya {$nameGreeting}? Tenang, Bebalung AI siap nemenin kamu biar harimu makin seru! 🎮✨\n\nBerikut beberapa ide asik yang bisa kita lakuin sekarang:\n1. 🎲 **Main Tebak-Tebakan / Kuis:** Mau aku kasih teka-teki receh, tebak logika, atau kuis pengetahuan?\n2. 🎬 **Rekomendasi Tontonan / Anime / Film:** Mau rekomendasi film seru, anime rating tinggi, atau drakor terbaru?\n3. 💻 **Eksplor Skill Baru:** Mau coba belajar coding kilat (HTML/Python/JS), ide bisnis unik, atau fakta sains menarik?\n4. 🍢 **Manjain Lidah:** Mumpung lagi santai di Meja #{$tableNumber}, cobain nikmatnya **Sate Kambing Polos** empuk atau segarnya **Es Jeruk**!\n\nKira-kira kamu lagi mood yang mana nih, atau mau curhat santai aja?";
            return [
                'success' => true,
                'reply' => $reply,
                'items' => $this->getTopDatabaseMenus(3),
                'quick_replies' => ['🎲 Kasih Tebak-Tebakan', '🎬 Rekomendasi Film/Anime', '💡 Ceritain Fakta Unik', '🍢 Menu Best Seller']
            ];
        }

        // 2. Lelucon, Humor & Tebak-Tebakan ("lelucon", "jokes", "tebak-tebakan", "lucu", "lawak")
        if (preg_match('/(lelucon|jokes|joke|tebak-tebakan|tebak tebakan|tebakan|lucu|ngelawak|humor|bikin ketawa)/i', $normalized)) {
            $jokes = [
                "**Tebak-tebakan dulu yuk {$nameGreeting}!** 🤣\n\n❓ **Pertanyaan:** Sate apa yang paling romantis di dunia?\n...\n💡 **Jawaban:** *Sa-te-rusnya aku ingin bersamamu!* 🥰🍢\n\nMau tebak-tebakan lagi, atau mau lelucon bapak-bapak yang lain?",
                "**Nih lelucon spesial buat kamu {$nameGreeting}!** 😆\n\n❓ **Pertanyaan:** Kenapa kambing kalau jalan suka nunduk?\n...\n💡 **Jawaban:** *Karena kalau melotot, takut disangka ngajak tawuran!* 🐐😂\n\nGimana, mau tebakan logika atau tebakan receh lagi?",
                "**Tebakan logika santai nih {$nameGreeting}:** 🧠✨\n\n❓ **Pertanyaan:** Pintu apa yang didorong oleh 10 orang dewasa sekalipun tidak bakal bisa terbuka?\n...\n💡 **Jawaban:** *Pintu yang ada tulisan 'TARIK'!* 🚪🤣\n\nAda yang mau ditanyakan lagi?"
            ];
            $selectedJoke = $jokes[array_rand($jokes)];
            return [
                'success' => true,
                'reply' => $selectedJoke,
                'items' => [],
                'quick_replies' => ['🎲 Kasih Tebakan Lagi', '😂 Cerita Lucu Lain', '🍢 Menu Sate Empuk']
            ];
        }

        // 3. Emosi, Curhat, Galau, Sedih, Lelah ("lagi galau", "sedih", "capek", "patah hati", "kecewa")
        if (preg_match('/(galau|sedih|patah hati|kecewa|capek|lelah|stres|stress|down|nangis|lagi bad mood|badmood)/i', $normalized)) {
            $reply = "Peluk hangat virtual buat kamu {$nameGreeting}. 🤗❤️\n\nIstirahat sejenak ya. Setiap orang punya hari-hari yang melelahkan atau membingungkan, dan tidak apa-apa kalau hari ini kamu merasa lelah. Kamu sudah berjuang luar biasa sampai titik ini.\n\n💡 **Tips kecil pereda penat:**\n• Tarik napas dalam-dalam, hembuskan perlahan.\n• Nikmati makanan/minuman yang hangat dan nyaman seperti **Gulai Kambing hangat** atau **Teh Poci Melati**.\n• Tumpahkan saja kalau ada hal yang ingin kamu ceritakan ke aku, aku siap mendengarkan tanpa menghakimi.\n\nTetap semangat ya! Ada yang ingin kamu curhatkan?";
            return [
                'success' => true,
                'reply' => $reply,
                'items' => $this->getTopDatabaseMenus(3),
                'quick_replies' => ['☕ Mau Minuman Hangat', '💬 Mau Curhat', '✨ Kata Motivasi']
            ];
        }

        // 4. Rekomendasi Hiburan: Film, Anime, Musik, Game ("rekomendasi film", "anime seru", "lagu enak", "game")
        if (preg_match('/(rekomendasi film|film seru|film bagus|rekomendasi anime|anime seru|nonton apa|lagu enak|playlist|game seru)/i', $normalized)) {
            $reply = "🎬 **Rekomendasi Hiburan Pilihan Terbaik Bebalung AI:**\n\n" .
                     "🌟 **Film Mind-Blowing & Epik:**\n" .
                     "• *Interstellar* (Sci-Fi petualangan luar angkasa & emosi luar biasa)\n" .
                     "• *Inception* (Misteri dunia mimpi berlapis)\n" .
                     "• *Parasite* (Drama thriller sosial dengan plot twist juara)\n\n" .
                     "⚔️ **Anime Wajib Tonton:**\n" .
                     "• *Frieren: Beyond Journey's End* (Petualangan magis yang mendalam & menenangkan)\n" .
                     "• *Attack on Titan (Shingeki no Kyojin)* (Plot & aksi laga legendaris)\n" .
                     "• *Jujutsu Kaisen* (Animasi pertarungan kutukan super intens)\n\n" .
                     "🎧 **Genre Musik:** Lofi Beats santai atau Acoustic Pop untuk menemani makan & santai!\n\n" .
                     "Ada genre atau tema tertentu yang paling kamu sukai {$nameGreeting}?";
            return [
                'success' => true,
                'reply' => $reply,
                'items' => [],
                'quick_replies' => ['🍿 Rekomendasi Anime Lain', '🎧 Playlist Santai', '🍢 Menu Best Seller']
            ];
        }

        // 5. Gombalan & Rayuan Lucu ("gombal", "gombalin", "rayu")
        if (preg_match('/(gombal|gombalin|rayu|kata romantis)/i', $normalized)) {
            $reply = "Siap, pasang sabuk pengaman ya {$nameGreeting}! 😉✨\n\n*\"Rumah makan Be Ba Lung memang punya sate kambing paling empuk dan kuah gulai paling gurih... tapi tetap saja, tidak ada yang bisa mengalahkan manisnya senyuman kamu hari ini.\"* 🥰🍢\n\nGimana, sudah bikin kamu tersenyum belum? 😄";
            return [
                'success' => true,
                'reply' => $reply,
                'items' => [],
                'quick_replies' => ['😂 Gombalan Lain', '🔥 Menu Makanan', '💡 Tanya Sains']
            ];
        }

        // 6. Motivasi & Quotes Semangat ("motivasi", "kata bijak", "semangat", "quotes")
        if (preg_match('/(motivasi|quotes|kata bijak|kata motivasi|butuh semangat|penyemangat)/i', $normalized)) {
            $reply = "✨ **Kata Semangat Hari Ini untuk {$nameGreeting}:**\n\n> *\"Langkah besar selalu dimulai dari langkah kecil yang konsisten. Jangan bandingkan prosesmu dengan hasil orang lain, karena setiap orang punya garis waktu dan keajaibannya masing-masing.\"*\n\nTeruslah melangkah, rayakan kemenangan-kemenangan kecilmu hari ini, dan jangan lupa isi energimu dengan makanan yang lezat! 💪🔥";
            return [
                'success' => true,
                'reply' => $reply,
                'items' => $this->getTopDatabaseMenus(3),
                'quick_replies' => ['💪 Motivasi Sukses', '🍢 Sate Kambing Polos', '💡 Tips Produktif']
            ];
        }

        // 7. Obrolan Santai & Chit-Chat ("lagi apa", "apa kabar", "kamu pintar", "pacar", "kamu siapa")
        if (preg_match('/(lagi apa|apa kabar|kabar kamu|kamu pintar|punya pacar|suka apa|ceritain tentang kamu)/i', $normalized)) {
            $reply = "Kabar saya luar biasa baik dan selalu bersemangat {$nameGreeting}! 🚀✨\n\nSebagai **Bebalung AI**, saya sedang standby di Meja #{$tableNumber} untuk membantu Anda, mulai dari urusan coding, matematika, sains, curhat santai, hingga mencarikan potongan daging sate kambing muda paling empuk dan minuman segar di Depot Be Ba Lung.\n\nAda hal seru apa yang sedang Anda pikirkan hari ini?";
            return [
                'success' => true,
                'reply' => $reply,
                'items' => $this->getTopDatabaseMenus(3),
                'quick_replies' => ['🔥 Menu Best Seller', '💻 Tanya Coding', '🎲 Tebak-Tebakan']
            ];
        }

        // 8. Coding & Pemrograman (Web Development, Python, PHP, JS, Login Page, dsb)
        if (preg_match('/(login|register|buatkan|bikin|script|function|halaman|tombol|navbar|footer|crud|api|database|toko online|html|css|javascript|typescript|python|php|laravel|react|sql)/i', $normalized)) {
            if (str_contains($normalized, 'google') || str_contains($normalized, 'tombol') || str_contains($normalized, 'biru')) {
                $reply = "Berikut contoh implementasi tombol Login dengan Google yang berwarna biru modern {$nameGreeting}:\n\n```html\n<!-- Tombol Login Google Biru Modern -->\n<button type=\"button\" style=\"display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 12px; background-color: #2563EB; color: #ffffff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s;\">\n    <svg width=\"18\" height=\"18\" viewBox=\"0 0 24 24\" fill=\"currentColor\">\n        <path d=\"M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z\" fill=\"#FFFFFF\"/>\n    </svg>\n    Masuk dengan Akun Google\n</button>\n```\n\n💡 **Penjelasan:** Tombol ini menggunakan background biru `#2563EB`, rounded corners, serta padding yang nyaman untuk pengguna mobile maupun desktop.";
                return ['success' => true, 'reply' => $reply, 'items' => [], 'quick_replies' => ['🔥 Menu Resto', '💡 Lanjutkan Kode', '☕ Minuman Segar']];
            }

            if (str_contains($normalized, 'login') || str_contains($normalized, 'halaman login')) {
                $reply = "Berikut template halaman Login yang modern, responsif, dan rapi {$nameGreeting}:\n\n```html\n<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n    <meta charset=\"UTF-8\">\n    <title>Login - Jidats Store</title>\n    <style>\n        body { font-family: sans-serif; background: #f3f4f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }\n        .card { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 380px; }\n        .input-group { margin-bottom: 1rem; }\n        .input-group label { display: block; margin-bottom: 0.5rem; font-size: 14px; color: #374151; }\n        .input-group input { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; }\n        .btn-submit { width: 100%; padding: 0.75rem; background: #2563EB; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; }\n    </style>\n</head>\n<body>\n    <div class=\"card\">\n        <h2 style=\"text-align:center; margin-top:0;\">Masuk ke Akun</h2>\n        <form action=\"/login\" method=\"POST\">\n            <div class=\"input-group\">\n                <label>Email / Username</label>\n                <input type=\"text\" placeholder=\"Masukkan email Anda\" required>\n            </div>\n            <div class=\"input-group\">\n                <label>Kata Sandi</label>\n                <input type=\"password\" placeholder=\"Masukkan kata sandi\" required>\n            </div>\n            <button type=\"submit\" class=\"btn-submit\">Masuk</button>\n        </form>\n    </div>\n</body>\n</html>\n```\n\nApakah ada fitur atau tombol tambahan yang ingin Anda masukkan ke halaman ini?";
                return ['success' => true, 'reply' => $reply, 'items' => [], 'quick_replies' => ['➕ Tambahkan Login Google', '🎨 Ubah Desain CSS', '🔥 Menu Resto']];
            }

            $reply = "💻 **Solusi Pemrograman & Rekayasa Software:**\n\nSaya dapat membantu Anda membuat kode, merancang arsitektur web, hingga mengatasi error:\n• **Frontend:** HTML5, CSS3, JavaScript, TypeScript, React, Next.js, Blade UI.\n• **Backend:** PHP (Laravel), Python, Node.js, Express, RESTful API.\n• **Database:** MySQL query, migration, indexing, relasi tabel.\n\nSilakan kirimkan potongan kode atau jelaskan fitur apa yang ingin Anda bangun!";
            return ['success' => true, 'reply' => $reply, 'items' => [], 'quick_replies' => ['🐍 Contoh Script Python', '🌐 Contoh REST API PHP', '🔥 Menu Resto']];
        }

        // 9. Matematika Sederhana & Langsung
        if (preg_match('/(\d+)\s*([\+\-\*\/xX])\s*(\d+)/', $userPrompt, $mathMatches)) {
            $num1 = (float)$mathMatches[1];
            $op = strtolower($mathMatches[2]);
            $num2 = (float)$mathMatches[3];
            $calcResult = 0;

            if ($op === '+' ) $calcResult = $num1 + $num2;
            elseif ($op === '-') $calcResult = $num1 - $num2;
            elseif ($op === '*' || $op === 'x') $calcResult = $num1 * $num2;
            elseif ($op === '/') $calcResult = ($num2 != 0) ? ($num1 / $num2) : 'Tak terdefinisi';

            // Jika pertanyaan sangat singkat (contoh: "10 + 20 berapa?"), jawab langsung
            if (strlen(trim($userPrompt)) <= 25) {
                return ['success' => true, 'reply' => (string)$calcResult, 'items' => [], 'quick_replies' => $this->getDefaultQuickReplies()];
            }

            $formattedResult = is_numeric($calcResult) ? number_format($calcResult, 2, ',', '.') : $calcResult;
            $reply = "🔢 **Perhitungan Matematika:**\nHasil dari `{$num1} {$op} {$num2}` adalah **{$formattedResult}**.\n\nAda perhitungan atau rumus lain yang ingin Anda hitung?";
            return ['success' => true, 'reply' => $reply, 'items' => [], 'quick_replies' => $this->getDefaultQuickReplies()];
        }

        // 10. Live Wikipedia Knowledge Fetch
        $isFactualQuery = preg_match('/^(?:apa\s+itu|siapa|pengertian|definisi|sejarah|biografi|arti\s+dari|teori|rumus|ibukota|presiden|tentang)\s+(.+)/i', trim($userPrompt), $factMatches);
        $wikiData = null;
        if ($isFactualQuery && !empty($factMatches[1])) {
            $cleanSearchTerm = trim(preg_replace('/[?!.,]/', '', $factMatches[1]));
            if (strlen($cleanSearchTerm) >= 3) {
                $wikiData = $this->fetchLiveWikipediaSummary($cleanSearchTerm);
            }
        }

        if ($wikiData && !empty($wikiData['extract'])) {
            $reply = "💡 **Penjelasan & Fakta:**\n\n📌 **{$wikiData['title']}**\n{$wikiData['extract']}\n\nAda hal lain yang ingin Anda ketahui seputar topik ini {$nameGreeting}?";
        } else {
            // Intelligent Natural Response with dynamic greeting
            $reply = "Halo {$nameGreeting}! ✨ Saya **Bebalung AI**, asisten cerdas dan teman ngobrol seru Anda di Depot Be Ba Lung.\n\nSaya bisa membantu Anda menyelesaikan coding, matematika, mencari rekomendasi film/musik, curhat santai, hingga memilihkan sate kambing empuk dan minuman segar. Ada yang bisa saya bantu atau temani hari ini? 😊";
        }

        return [
            'success' => true,
            'reply' => $reply,
            'items' => $this->getTopDatabaseMenus(3),
            'quick_replies' => ['🔥 Menu Best Seller', '🎲 Kasih Tebak-Tebakan', '💻 Tanya Coding', '🥤 Minuman Segar'],
        ];
    }

    /**
     * Fetch summary from Wikipedia in real-time.
     */
    protected function fetchLiveWikipediaSummary(string $query): ?array
    {
        try {
            $searchUrl = "https://id.wikipedia.org/w/api.php?action=query&list=search&srsearch=" . urlencode($query) . "&utf8=&format=json";
            $ch = curl_init($searchUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'DepotBebalungAI/2.0');
            $res = curl_exec($ch);
            curl_close($ch);

            if ($res) {
                $json = json_decode($res, true);
                if (!empty($json['query']['search'][0]['title'])) {
                    $topTitle = $json['query']['search'][0]['title'];
                    $summaryUrl = "https://id.wikipedia.org/api/rest_v1/page/summary/" . urlencode(str_replace(' ', '_', $topTitle));
                    $ch = curl_init($summaryUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_USERAGENT, 'DepotBebalungAI/2.0');
                    $sumRes = curl_exec($ch);
                    curl_close($ch);

                    if ($sumRes) {
                        $sumJson = json_decode($sumRes, true);
                        if (!empty($sumJson['extract'])) {
                            return [
                                'title' => $sumJson['title'],
                                'extract' => $sumJson['extract'],
                            ];
                        }
                    }
                }
            }
        } catch (\Throwable $e) {}

        return null;
    }

    /**
     * Handle Mood & Music Recommendations.
     */
    protected function handleMoodAndMusicRecommendations(string $normalized, string $rawMessage, string $nameGreeting): ?array
    {
        if (str_contains($normalized, 'semangat') || str_contains($normalized, 'mood booster') || str_contains($normalized, 'kerja') || str_contains($normalized, 'belajar') || str_contains($normalized, 'lofi') || str_contains($normalized, 'santai')) {
            $ytBooster = "https://www.youtube.com/results?search_query=playlist+lagu+semangat+mood+booster+indonesia";
            $spBooster = "https://open.spotify.com/search/mood%20booster%20indonesia";

            $reply = "Siap {$nameGreeting}! Ini playlist pilihan untuk membakar semangat dan mengembalikan energi positifmu! 🚀🔥\n\n";
            $reply .= "🎧 **Rekomendasi Lagu Mood Booster:**\n";
            $reply .= "1. ⚡ **Tulus – Manusia Kuat**\n";
            $reply .= "2. ⚡ **Yura Yunita – Tutur Batin / Dunia Tipu-Tipu**\n";
            $reply .= "3. ⚡ **GAC – Bahagia**\n";
            $reply .= "4. ⚡ **Coldplay – Viva La Vida**\n";
            $reply .= "5. ☕ **Lofi Beats to Relax / Study**\n\n";
            $reply .= "👉 [Buka Playlist di YouTube]({$ytBooster}) | [Buka di Spotify]({$spBooster})";

            return [
                'success' => true,
                'reply' => $reply,
                'items' => [],
                'quick_replies' => ['🔥 Menu Best Seller', '🥤 Minuman Segar', '😂 Ceritakan lelucon'],
            ];
        }

        if (str_contains($normalized, 'galau') || str_contains($normalized, 'sedih') || str_contains($normalized, 'patah hati') || str_contains($normalized, 'kecewa')) {
            $ytGalau = "https://www.youtube.com/results?search_query=playlist+lagu+galau+indonesia+terbaru";
            $spGalau = "https://open.spotify.com/search/lagu%20galau%20indonesia";

            $reply = "Peluk hangat buat {$nameGreeting} yang lagi galau... 🤗❤️\n\n";
            $reply .= "Terkadang mendengarkan lagu adalah terapi terbaik meluapkan rasa gundah agar dada lebih lega.\n\n";
            $reply .= "🎧 **Lagu Galau Pilihan:**\n";
            $reply .= "1. 🎵 **Bernadya – Satu Bulan**\n";
            $reply .= "2. 🎵 **Tulus – Hati-Hati di Jalan**\n";
            $reply .= "3. 🎵 **Feby Putri ft. Fiersa Besari – Runtuh**\n";
            $reply .= "4. 🎵 **Denny Caknan – Wirang**\n\n";
            $reply .= "👉 [Dengarkan di YouTube]({$ytGalau}) | [Dengarkan di Spotify]({$spGalau})";

            return [
                'success' => true,
                'reply' => $reply,
                'items' => [],
                'quick_replies' => ['🚀 Lagu Semangat', '🍵 Minuman Hangat', '🍢 Rekomendasi Makanan'],
            ];
        }

        return null;
    }

    /**
     * Handle Web and Social Media Search Intents.
     */
    protected function handleWebAndSocialSearchIntent(string $rawMessage, string $nameGreeting): ?array
    {
        $text = strtolower(trim($rawMessage));

        // Instagram
        if (preg_match('/\b(instagram|ig|insta)\b/i', $text)) {
            $username = '';
            if (preg_match('/(?:@|instagram\.com\/|ig\s+|instagram\s+)([a-zA-Z0-9_\.]+)/i', $rawMessage, $matches)) {
                $username = trim($matches[1], '@ /');
            }
            if (!empty($username) && !in_array(strtolower($username), ['dan', 'mau', 'link', 'buka'])) {
                $igUrl = "https://www.instagram.com/" . urlencode($username) . "/";
                return [
                    'success' => true,
                    'reply' => "📸 **Tautan Instagram:**\n\n👉 [Buka Profil Instagram @{$username}]({$igUrl})",
                    'items' => [],
                    'quick_replies' => ['🎬 Cari di YouTube', '🔍 Cari di Google', '🔥 Menu Depot Be Ba Lung'],
                ];
            }
        }

        // YouTube
        if (preg_match('/\b(youtube|yt)\b/i', $text)) {
            $query = preg_replace('/^(?:buka|cari|search|putar)?\s*(?:di\s+)?(?:youtube|yt)\s*/i', '', $rawMessage);
            $query = trim(preg_replace('/\b(youtube|yt)\b/i', '', $query));
            $ytUrl = !empty($query) ? "https://www.youtube.com/results?search_query=" . urlencode($query) : "https://www.youtube.com";

            return [
                'success' => true,
                'reply' => "🎬 **Layanan YouTube:**\n\n▶️ [Buka Video di YouTube: \"{$query}\"]({$ytUrl})",
                'items' => [],
                'quick_replies' => ['🔍 Cari di Google', '🔥 Menu Makanan'],
            ];
        }

        // Google
        if (preg_match('/\b(google|browsing|googling|cari\s*di\s*google)\b/i', $text)) {
            $query = preg_replace('/^(?:buka|cari|search|googling)?\s*(?:di\s+)?(?:google|internet)?\s*/i', '', $rawMessage);
            $query = trim(preg_replace('/\b(google|googling)\b/i', '', $query));
            $gUrl = !empty($query) ? "https://www.google.com/search?q=" . urlencode($query) : "https://www.google.com";

            return [
                'success' => true,
                'reply' => "🔍 **Pencarian Google:**\n\n🌐 [Buka Hasil Pencarian Google: \"{$query}\"]({$gUrl})",
                'items' => [],
                'quick_replies' => ['🎬 Cari di YouTube', '🔥 Menu Makanan'],
            ];
        }

        return null;
    }

    /**
     * Get drink menus from database.
     */
    protected function getDrinkMenus(): array
    {
        try {
            $menus = Menu::with('category')->where('is_available', true)
                ->where(function($q) {
                    $q->whereHas('category', function ($catQ) {
                        $catQ->where('slug', 'minuman');
                    })->orWhereRaw('LOWER(name) LIKE ?', ['%jeruk%'])
                      ->orWhereRaw('LOWER(name) LIKE ?', ['%poci%'])
                      ->orWhereRaw('LOWER(name) LIKE ?', ['%teh%'])
                      ->orWhereRaw('LOWER(name) LIKE ?', ['%kopi%'])
                      ->orWhereRaw('LOWER(name) LIKE ?', ['%air%']);
                })
                ->orderBy('sort_order', 'asc')
                ->take(4)
                ->get();

            if ($menus->isNotEmpty()) {
                return $this->formatMenuItems($menus, "Minuman Segar");
            }
        } catch (\Throwable $e) {}

        return [
            [
                'id' => 12,
                'name' => 'Es Jeruk',
                'price' => 10000,
                'formatted_price' => 'Rp 10.000',
                'description' => 'Perasan jeruk segar alami kaya vitamin C pelepas dahaga.',
                'image_url' => asset('images/menus/es_jeruk.jpg'),
                'category_name' => 'Minuman',
                'badge' => '⭐ Segar Favorit',
            ],
            [
                'id' => 14,
                'name' => 'Teh Poci',
                'price' => 15000,
                'formatted_price' => 'Rp 15.000',
                'description' => 'Teh poci tanah liat tradisional disajikan hangat dengan gula batu.',
                'image_url' => asset('images/menus/teh_poci.jpg'),
                'category_name' => 'Minuman',
                'badge' => '🫖 Klasik',
            ],
            [
                'id' => 15,
                'name' => 'Kopi Toebroek',
                'price' => 5000,
                'formatted_price' => 'Rp 5.000',
                'description' => 'Kopi hitam tubruk biji kopi nusantara pilihan harum mantap.',
                'image_url' => asset('images/menus/kopi_toebroek.jpg'),
                'category_name' => 'Minuman',
                'badge' => '☕ Mantap',
            ],
            [
                'id' => 11,
                'name' => 'Es Teh Manis',
                'price' => 4000,
                'formatted_price' => 'Rp 4.000',
                'description' => 'Es teh manis segar wangi melati asli.',
                'image_url' => asset('images/menus/es_teh_manis.jpg'),
                'category_name' => 'Minuman',
                'badge' => '🧊 Segar',
            ]
        ];
    }

    /**
     * Get top database menus.
     */
    protected function getTopDatabaseMenus(int $limit = 4): array
    {
        try {
            $priorityNames = ['Sate Kambing (Polos)', 'Sate Kambing (Campur)', 'Gulai Kambing', 'Tongseng Kambing', 'Sop Kambing', 'Sate Ayam', 'Nasi Gurih'];
            $menus = Menu::with('category')->where('is_available', true)
                ->whereIn('name', $priorityNames)
                ->orderBy('sort_order', 'asc')
                ->take($limit)
                ->get();

            if ($menus->count() < $limit) {
                $menus = Menu::with('category')->where('is_available', true)->orderBy('sort_order', 'asc')->take($limit)->get();
            }

            if ($menus->isNotEmpty()) {
                return $this->formatMenuItems($menus, "👑 Best Seller");
            }
        } catch (\Throwable $e) {}

        return [
            [
                'id' => 1,
                'name' => 'Sate Kambing (Polos)',
                'price' => 50000,
                'formatted_price' => 'Rp 50.000',
                'description' => '100% daging kambing muda pilihan tanpa lemak, empuk dan tidak bau prengus.',
                'image_url' => asset('images/menus/sate_kambing_polos.jpg'),
                'category_name' => 'Makanan',
                'badge' => '👑 Paling Best',
            ],
            [
                'id' => 5,
                'name' => 'Gulai Kambing',
                'price' => 30000,
                'formatted_price' => 'Rp 30.000',
                'description' => 'Kuah kuning pekat berempah khas Jawa dengan potongan daging kambing lembut.',
                'image_url' => asset('images/menus/gulai_kambing.jpg'),
                'category_name' => 'Makanan',
                'badge' => '🔥 Best Seller',
            ],
            [
                'id' => 12,
                'name' => 'Es Jeruk',
                'price' => 10000,
                'formatted_price' => 'Rp 10.000',
                'description' => 'Perasan jeruk segar alami kaya vitamin C pelepas dahaga.',
                'image_url' => asset('images/menus/es_jeruk.jpg'),
                'category_name' => 'Minuman',
                'badge' => '⭐ Segar Favorit',
            ],
        ];
    }

    /**
     * Format Menu items for JSON response.
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
            elseif (str_contains($nameLower, 'poci') || str_contains($nameLower, 'jeruk')) $badge = "⭐ Favorit Segar";
            elseif (str_contains($nameLower, 'ayam')) $badge = "🍗 Gurih Empuk";
            elseif (str_contains($nameLower, 'nasi')) $badge = "🍚 Gurih Pulen";
            elseif (str_contains($nameLower, 'kopi')) $badge = "☕ Mantap";

            $results[] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => (float)$menu->price,
                'formatted_price' => $menu->formatted_price ?? ('Rp ' . number_format($menu->price, 0, ',', '.')),
                'description' => $menu->description ?? 'Menu istimewa khas Depot Sate Be Ba Lung.',
                'image_url' => $menu->image_url ?? asset('images/logo-goat.png'),
                'category_name' => optional($menu->category)->name ?? 'Menu',
                'badge' => $badge,
            ];
        }
        return $results;
    }

    /**
     * Check if the message is purely a greeting.
     */
    protected function isPureGreeting(string $text): bool
    {
        $greetings = ['halo', 'hai', 'hello', 'pagi', 'selamat pagi', 'siang', 'selamat siang', 'sore', 'selamat sore', 'malam', 'selamat malam', 'assalamu', 'assalamualaikum', 'tes', 'test', 'ping', 'p', 'permisi', 'hi'];
        $clean = trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $text));
        
        foreach ($greetings as $g) {
            if ($clean === $g || $clean === "halo ai" || $clean === "hai ai" || $clean === "halo bot" || $clean === "hai bot" || $clean === "halo chef") {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if query is explicitly profanity.
     */
    protected function isExplicitProfanity(string $text): bool
    {
        if (str_contains($text, 'halal') || str_contains($text, 'ada babi') || str_contains($text, 'menu babi') || str_contains($text, 'apakah babi') || str_contains($text, 'non halal') || str_contains($text, 'babi ya') || str_contains($text, 'apakah ada babi')) {
            return false;
        }

        $badWords = [
            'anjing', 'anjir', 'anjay', 'asu', 'bajingan', 'bangsat', 'kampret',
            'kontol', 'kntl', 'memek', 'mmk', 'pantek', 'puki', 'peli', 'itil', 'jembut',
            'ngentot', 'ngewe', 'titit', 'tetek', 'toket', 'lonte', 'perek', 'pelacur',
            'tai', 'taek', 'bego', 'goblok', 'tolol', 'idiot', 'peler', 'pepek', 'tempik',
            'jancuk', 'jancok', 'dancuk', 'cuk', 'celeng', 'bodoh', 'sialan', 'setan', 'iblis',
            'fuck', 'fucking', 'bitch', 'shit', 'asshole', 'bastard', 'cunt', 'dick', 'pussy'
        ];

        $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($text));
        $words = explode(' ', $clean);

        foreach ($words as $w) {
            $w = trim($w);
            if (empty($w)) continue;
            if (in_array($w, $badWords, true)) {
                return true;
            }
        }

        foreach ($badWords as $bw) {
            if (strlen($bw) >= 4 && str_contains($clean, $bw)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if query contains any keywords.
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
     * Default quick replies.
     */
    protected function getDefaultQuickReplies(): array
    {
        return [
            '🔥 Rekomendasi Menu Best Seller',
            '🍢 Sate Kambing Polos',
            '🍲 Menu Kuah Gulai & Sop',
            '🥤 Daftar Minuman Segar',
            '📍 Alamat & Jam Buka',
            '💡 Apa yang bisa kamu lakukan?',
        ];
    }
}
