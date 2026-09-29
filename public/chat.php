<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// API Key Gemini AI
$apiKey = getenv('GEMINI_API_KEY') ?: ($_ENV['GEMINI_API_KEY'] ?? '');

$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);
$userMessage = trim($data['message'] ?? '');
$history = $data['history'] ?? [];
$tableNumber = $data['table_number'] ?? ($data['tableNumber'] ?? '01');
$customerName = $data['customer_name'] ?? ($data['customerName'] ?? 'Pelanggan');

if (empty($userMessage)) {
    echo json_encode([
        'success' => true,
        'reply' => "Halo Kak! ✨ Selamat datang di **Depot Sate & Gulai Be Ba Lung** (Meja #{$tableNumber}). Saya **BEBALUNG AI**, siap membantu Anda seputar menu restoran lezat, rekomendasi kuliner, hingga tanya jawab umum, coding, lagu, film, dan sains. Ada yang bisa saya bantu? 🚀",
        'quick_replies' => ['🔥 Best Seller', '🎵 Rekomendasi Lagu', '🍢 Sate Kambing Polos', '💻 Tanya Coding']
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// System Instruction Lengkap untuk Bebalung AI Universal
$systemPrompt = <<<PROMPT
Kamu adalah BEBALUNG AI, asisten kecerdasan buatan universal, cerdas, ramah, dan serba bisa di restoran Bebalung (pelanggan berada di Meja #{$tableNumber}).

Tugas & Aturan Utama:
1. Kemampuan Universal:
   - Jawab pertanyaan apa pun secara akurat, cerdas, empatik, dan natural (sains, matematika, coding/pemrograman, lagu/musik, film, curhat, tebak-tebakan, sastra, bisnis, dan pengetahuan umum).
   - Berikan jawaban yang terstruktur rapi dengan format markdown jika diperlukan, to the point, dan tidak mengarang fakta.

2. Sikap & Konteks Respons (SANGAT PENTING):
   - Jika pengguna bertanya tentang lagu galau/musik, berikan rekomendasi daftar lagu sungguhan (lengkap dengan penyanyi/judul) secara empati dan relevan, BUKAN menawarkan menu makanan atau sate kambing.
   - Jika pengguna bertanya tentang coding, berikan kode/solusi pemrograman yang tepat dan rapi.
   - JANGAN MEMAKSAKAN pembahasan menu restoran kecuali pengguna menanyakannya atau berniat memesan makanan/minuman.

3. Pengetahuan Menu Restoran (Hanya jika ditanya seputar makanan/minuman):
   - Sate Kambing (Polos): Rp 50.000 (100% daging kambing muda empuk tanpa lemak & tanpa bau prengus)
   - Sate Kambing (Campur): Rp 45.000 (Daging kambing muda lezat diselingi lemak juicy)
   - Gulai Kambing: Rp 30.000 (Kuah kuning kental rempah gurih khas)
   - Tongseng Kambing: Rp 35.000 (Kuah gulai tumis pedas manis dengan kubis & tomat segar)
   - Sop Kambing: Rp 30.000 (Kuah kaldu bening gurih rempah jahe)
   - Sate Ayam: Rp 20.000 (Fillet ayam bumbu kacang/kecap)
   - Nasi Gurih: Rp 7.500 | Nasi Putih: Rp 6.000
   - Minuman Segar: Es Teh (Rp 4.000), Es Jeruk (Rp 10.000), Teh Poci Gula Batu (Rp 15.000), Kopi Toebroek (Rp 5.000)
   - Semua menu 100% HALAL.
PROMPT;

// Urutan model Gemini aktif (dengan auto fallback)
$models = ['gemini-3.5-flash-lite', 'gemini-3.1-flash-lite', 'gemini-3.6-flash', 'gemini-3.5-flash', 'gemini-flash-lite-latest', 'gemini-2.5-flash'];
$aiReply = null;

foreach ($models as $modelName) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key=" . urlencode($apiKey);

    $contents = [];
    $contents[] = [
        'role' => 'user',
        'parts' => [['text' => $systemPrompt]]
    ];
    $contents[] = [
        'role' => 'model',
        'parts' => [['text' => "Dimengerti, saya siap menjadi BEBALUNG AI yang universal, cerdas, empatik, dan akurat."]]
    ];

    if (is_array($history) && count($history) > 0) {
        $recentHistory = array_slice($history, -8);
        foreach ($recentHistory as $turn) {
            $role = ($turn['sender'] ?? '') === 'user' ? 'user' : 'model';
            $text = trim($turn['text'] ?? '');
            if (!empty($text)) {
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => $text]]
                ];
            }
        }
    }

    $contents[] = [
        'role' => 'user',
        'parts' => [['text' => $userMessage]]
    ];

    $payload = [
        'contents' => $contents,
        'generationConfig' => [
            'temperature' => 0.7,
            'maxOutputTokens' => 1200,
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        $result = json_decode($response, true);
        $candidateText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
        if (!empty(trim($candidateText))) {
            $aiReply = trim($candidateText);
            break;
        }
    }
}

if ($aiReply === null) {
    $aiReply = "Maaf, saat ini koneksi AI sedang sibuk. Silakan tanyakan kembali ya Kak! ✨";
}

echo json_encode([
    'success' => true,
    'reply' => $aiReply,
    'quick_replies' => [
        '🔥 Rekomendasi Menu',
        '🎵 Rekomendasi Lagu',
        '🍢 Sate Kambing Polos',
        '💻 Tanya Coding'
    ]
], JSON_UNESCAPED_UNICODE);
