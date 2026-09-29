import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';

dotenv.config();

const app = express();
const PORT = process.env.PORT || 3000;
const GEMINI_API_KEY = process.env.GEMINI_API_KEY || "";

app.use(cors());
app.use(express.json());

const SYSTEM_PROMPT = `
Kamu adalah BEBALUNG AI, asisten kecerdasan buatan universal, cerdas, ramah, dan serba bisa di restoran Bebalung (pelanggan berada di Meja #01).

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
`;

const GEMINI_MODELS = [
  'gemini-3.6-flash',
  'gemini-3.7-flash',
  'gemini-flash-latest',
  'gemini-2.5-flash-lite',
  'gemini-2.0-flash',
  'gemini-1.5-flash'
];

app.post('/api/chat', async (req, res) => {
  try {
    const { message, history = [], table_number = '01' } = req.body;

    if (!message || !message.trim()) {
      return res.json({
        success: true,
        reply: "Halo Kak! ✨ Selamat datang di **Depot Sate & Gulai Be Ba Lung** (Meja #" + table_number + "). Saya **BEBALUNG AI**, siap membantu Anda seputar menu lezat, rekomendasi kuliner, hingga tanya jawab umum, coding, lagu, dan sains. Ada yang bisa saya bantu? 🚀",
        quick_replies: ['🔥 Best Seller', '🎵 Rekomendasi Lagu', '🍢 Sate Kambing Polos', '💻 Tanya Coding']
      });
    }

    let aiReply = null;

    const contents = [
      { role: 'user', parts: [{ text: SYSTEM_PROMPT }] },
      { role: 'model', parts: [{ text: "Dimengerti, saya siap menjadi BEBALUNG AI yang universal, cerdas, empatik, dan akurat." }] }
    ];

    if (Array.isArray(history) && history.length > 0) {
      const recent = history.slice(-8);
      for (const turn of recent) {
        const role = turn.sender === 'user' ? 'user' : 'model';
        const text = (turn.text || '').trim();
        if (text) {
          contents.push({ role, parts: [{ text }] });
        }
      }
    }

    contents.push({ role: 'user', parts: [{ text: message.trim() }] });

    for (const modelName of GEMINI_MODELS) {
      try {
        const endpoint = `https://generativelanguage.googleapis.com/v1beta/models/${modelName}:generateContent?key=${encodeURIComponent(GEMINI_API_KEY)}`;
        const response = await fetch(endpoint, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            contents,
            generationConfig: {
              temperature: 0.7,
              maxOutputTokens: 1200
            }
          })
        });

        if (response.ok) {
          const json = await response.json();
          const candidateText = json.candidates?.[0]?.content?.parts?.[0]?.text;
          if (candidateText && candidateText.trim()) {
            aiReply = candidateText.trim();
            break;
          }
        }
      } catch (err) {
        // try next model
      }
    }

    if (!aiReply) {
      aiReply = "Maaf, saat ini koneksi AI sedang sibuk. Silakan tanyakan kembali ya Kak! ✨";
    }

    return res.json({
      success: true,
      reply: aiReply,
      quick_replies: ['🔥 Rekomendasi Menu', '🎵 Rekomendasi Lagu', '🍢 Sate Kambing Polos', '💻 Tanya Coding']
    });

  } catch (err) {
    console.error('Chat error:', err);
    return res.status(500).json({
      success: false,
      reply: "Maaf, terjadi kendala teknis pada server AI. Silakan coba sesaat lagi."
    });
  }
});

app.get('/', (req, res) => {
  res.send('Bebalung AI Backend Server is Running.');
});

app.listen(PORT, () => {
  console.log(`Bebalung AI backend server running on port ${PORT}`);
});
