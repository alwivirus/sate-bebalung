<!-- Chatbot Floating Button & Modal Component -->
<div id="bebalung-chatbot-container">
    <!-- Floating Trigger Button -->
    <div id="chatbot-fab" class="chatbot-fab" onclick="toggleChatbot()" title="Tanya Rekomendasi Menu ke Chef Bebalung AI">
        <div class="fab-avatar">
            <img src="{{ asset('images/logo-goat.png') }}" alt="Chef Bebalung AI">
            <span class="online-indicator"></span>
        </div>
        <div class="fab-label">
            <span class="fab-sparkle">✨</span>
            <span class="fab-text">Rekomendasi Menu</span>
        </div>
        <div class="fab-pulse"></div>
    </div>

    <!-- Chatbot Window Modal -->
    <div id="chatbot-modal" class="chatbot-modal">
        <!-- Chatbot Header -->
        <div class="chatbot-header">
            <div class="chat-header-info">
                <div class="chat-header-avatar">
                    <img src="{{ asset('images/logo-goat.png') }}" alt="Chef Bebalung">
                    <span class="status-dot"></span>
                </div>
                <div>
                    <h3 class="chat-title">Chef Bebalung AI</h3>
                    <p class="chat-subtitle"><i class="fa-solid fa-sparkles"></i> Asisten Kuliner Meja #<span id="chatTableNumber">{{ $tableNumber ?? '01' }}</span></p>
                </div>
            </div>
            <div class="chat-header-actions">
                <button type="button" class="chat-icon-btn" onclick="resetChat()" title="Mulai Ulang Chat">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>
                <button type="button" class="chat-icon-btn" onclick="toggleChatbot()" title="Tutup Chat">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <!-- Chatbot Message Body -->
        <div class="chatbot-body" id="chatbotMessages">
            <!-- Messages rendered dynamically via JS -->
        </div>

        <!-- Typing Indicator -->
        <div class="chat-typing-indicator" id="chatTyping" style="display: none;">
            <div class="typing-bubble">
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
            <span class="typing-text">Chef Bebalung sedang mencari menu terbaik...</span>
        </div>

        <!-- Quick Reply Chips Area -->
        <div class="chat-quick-replies" id="chatQuickReplies">
            <!-- Quick chips injected by JS -->
        </div>

        <!-- Chatbot Input Footer -->
        <div class="chatbot-footer">
            <form id="chatbotForm" onsubmit="handleChatSubmit(event)">
                <input type="text" id="chatbotInput" placeholder="Ketik pertanyaan (misal: menu paling best, kuah rempah, budget 25rb)..." autocomplete="off">
                <button type="submit" id="chatbotSendBtn" class="chat-send-btn" title="Kirim">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    /* Chatbot Neo-Brutalist Theme */
    #bebalung-chatbot-container {
        font-family: 'Plus Jakarta Sans', sans-serif;
        position: fixed;
        z-index: 9998;
        bottom: 80px;
        right: 16px;
    }

    /* Floating Action Button (FAB) */
    .chatbot-fab {
        background-color: #FFB703;
        border: 2.5px solid #1E1E1E;
        border-radius: 40px;
        box-shadow: 3px 3px 0px #1E1E1E;
        padding: 4px 10px 4px 4px;
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        position: relative;
        user-select: none;
    }

    .chatbot-fab:hover {
        background-color: #F59E0B;
        transform: translateY(-2px);
        box-shadow: 4px 4px 0px #1E1E1E;
    }

    .chatbot-fab:active {
        transform: translate(1px, 1px);
        box-shadow: 1px 1px 0px #1E1E1E;
    }

    .fab-avatar {
        width: 36px;
        height: 36px;
        background: #FFFFFF;
        border: 2px solid #1E1E1E;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: visible;
        position: relative;
        flex-shrink: 0;
    }

    .fab-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .online-indicator {
        position: absolute;
        bottom: -1px;
        right: -1px;
        width: 10px;
        height: 10px;
        background-color: #10B981;
        border: 1.5px solid #1E1E1E;
        border-radius: 50%;
    }

    .fab-label {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .fab-text {
        font-size: 0.78rem;
        font-weight: 900;
        color: #111827;
        white-space: nowrap;
        letter-spacing: 0.2px;
    }

    .fab-sparkle {
        font-size: 0.85rem;
        animation: rotateSparkle 2.5s infinite linear;
    }

    @keyframes rotateSparkle {
        0% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.25) rotate(180deg); }
        100% { transform: scale(1) rotate(360deg); }
    }

    .fab-pulse {
        position: absolute;
        top: -3px;
        left: -3px;
        right: -3px;
        bottom: -3px;
        border-radius: 40px;
        border: 2px solid #FFB703;
        opacity: 0;
        animation: pulseEffect 2s infinite;
        pointer-events: none;
    }

    @keyframes pulseEffect {
        0% { transform: scale(1); opacity: 0.8; }
        100% { transform: scale(1.15); opacity: 0; }
    }

    /* Chatbot Modal Window */
    .chatbot-modal {
        position: fixed;
        bottom: 80px;
        right: 16px;
        width: 360px;
        max-width: calc(100vw - 28px);
        height: 480px;
        max-height: calc(100dvh - 110px);
        background-color: #F9FAFB;
        border: 2.5px solid #1E1E1E;
        border-radius: 20px;
        box-shadow: 4px 6px 0px #1E1E1E;
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: modalPop 0.22s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        z-index: 9999;
    }

    @media (max-width: 480px) {
        #bebalung-chatbot-container {
            right: 12px;
            bottom: 78px;
        }

        .chatbot-fab {
            padding: 3px;
            border-radius: 50%;
            width: 46px;
            height: 46px;
            justify-content: center;
        }

        .fab-label {
            display: none;
        }

        .fab-pulse {
            border-radius: 50%;
        }

        .chatbot-modal {
            right: 12px;
            left: 12px;
            width: auto;
            max-width: none;
            bottom: 74px;
            height: 440px;
            max-height: calc(100dvh - 100px);
            border-radius: 18px;
        }
    }

    /* Header */
    .chatbot-header {
        background-color: #FFB703;
        border-bottom: 3px solid #1E1E1E;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chat-header-avatar {
        width: 40px;
        height: 40px;
        background: #FFFFFF;
        border: 2px solid #1E1E1E;
        border-radius: 50%;
        overflow: visible;
        position: relative;
        flex-shrink: 0;
    }

    .chat-header-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .status-dot {
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 11px;
        height: 11px;
        background-color: #10B981;
        border: 2px solid #1E1E1E;
        border-radius: 50%;
    }

    .chat-title {
        font-size: 0.95rem;
        font-weight: 900;
        color: #111827;
        margin: 0;
        line-height: 1.2;
    }

    .chat-subtitle {
        font-size: 0.72rem;
        font-weight: 700;
        color: #6B7280;
        margin: 2px 0 0 0;
    }

    .chat-header-actions {
        display: flex;
        gap: 6px;
    }

    .chat-icon-btn {
        width: 32px;
        height: 32px;
        background: #FFFFFF;
        border: 2px solid #1E1E1E;
        border-radius: 8px;
        box-shadow: 2px 2px 0px #1E1E1E;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1E1E1E;
        font-size: 0.85rem;
        transition: all 0.1s ease;
    }

    .chat-icon-btn:hover {
        background-color: #FEE2E2;
    }

    .chat-icon-btn:active {
        transform: translate(1px, 1px);
        box-shadow: 1px 1px 0px #1E1E1E;
    }

    /* Message Body */
    .chatbot-body {
        flex: 1;
        padding: 14px 12px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
        background: #F3F4F6;
        scroll-behavior: smooth;
    }

    /* Message Bubbles */
    .chat-bubble {
        max-width: 88%;
        padding: 10px 14px;
        border-radius: 16px;
        font-size: 0.85rem;
        line-height: 1.45;
        border: 2px solid #1E1E1E;
        word-wrap: break-word;
        box-shadow: 2px 2px 0px rgba(0,0,0,0.15);
    }

    .chat-bubble.bot {
        align-self: flex-start;
        background-color: #FFFFFF;
        color: #1F2937;
        border-bottom-left-radius: 4px;
    }

    .chat-bubble.user {
        align-self: flex-end;
        background-color: #FFB703;
        color: #111827;
        font-weight: 700;
        border-bottom-right-radius: 4px;
        box-shadow: 2px 2px 0px #1E1E1E;
    }

    .chat-bubble-text p {
        margin: 0 0 6px 0;
    }

    .chat-bubble-text p:last-child {
        margin-bottom: 0;
    }

    .chat-bubble-text strong {
        font-weight: 800;
        color: #111827;
    }

    /* Rich Food Cards in Bot Messages */
    .chat-food-cards {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 10px;
        width: 100%;
    }

    .chat-food-card {
        background: #FFFFFF;
        border: 2px solid #1E1E1E;
        border-radius: 14px;
        box-shadow: 3px 3px 0px #1E1E1E;
        padding: 8px 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: transform 0.1s ease;
    }

    .chat-food-card:hover {
        transform: translateY(-1px);
    }

    .chat-food-img {
        width: 58px;
        height: 58px;
        border-radius: 10px;
        border: 1.5px solid #1E1E1E;
        object-fit: cover;
        flex-shrink: 0;
        background: #E5E7EB;
    }

    .chat-food-details {
        flex: 1;
        min-width: 0;
    }

    .chat-food-badge {
        display: inline-block;
        font-size: 0.65rem;
        font-weight: 800;
        background-color: #FEF3C7;
        color: #B45309;
        border: 1px solid #D97706;
        padding: 1px 6px;
        border-radius: 6px;
        margin-bottom: 3px;
    }

    .chat-food-name {
        font-size: 0.84rem;
        font-weight: 800;
        color: #111827;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-food-price {
        font-size: 0.8rem;
        font-weight: 800;
        color: #DC2626;
        margin: 2px 0 0 0;
    }

    .chat-add-btn {
        background-color: #FFB703;
        border: 2px solid #1E1E1E;
        border-radius: 8px;
        box-shadow: 2px 2px 0px #1E1E1E;
        font-weight: 800;
        font-size: 0.72rem;
        padding: 6px 8px;
        cursor: pointer;
        color: #111827;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        flex-shrink: 0;
        transition: all 0.1s ease;
        white-space: nowrap;
    }

    .chat-add-btn:hover {
        background-color: #10B981;
        color: #FFFFFF;
    }

    .chat-add-btn:active {
        transform: translate(1px, 1px);
        box-shadow: 0px 0px 0px #1E1E1E;
    }

    /* Typing Indicator */
    .chat-typing-indicator {
        padding: 4px 14px 8px 14px;
        background: #F3F4F6;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .typing-bubble {
        background: #E5E7EB;
        border: 1.5px solid #1E1E1E;
        border-radius: 12px;
        padding: 6px 10px;
        display: inline-flex;
        gap: 4px;
    }

    .typing-bubble .dot {
        width: 6px;
        height: 6px;
        background: #4B5563;
        border-radius: 50%;
        animation: typingDot 1.4s infinite ease-in-out both;
    }

    .typing-bubble .dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-bubble .dot:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typingDot {
        0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
        40% { transform: scale(1.1); opacity: 1; }
    }

    .typing-text {
        font-size: 0.72rem;
        font-weight: 700;
        color: #6B7280;
        font-style: italic;
    }

    /* Quick Reply Chips */
    .chat-quick-replies {
        background: #FFFFFF;
        border-top: 2px solid #E5E7EB;
        padding: 8px 12px;
        display: flex;
        gap: 6px;
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none;
    }

    .chat-quick-replies::-webkit-scrollbar {
        display: none;
    }

    .quick-chip {
        background: #FEF3C7;
        border: 1.5px solid #1E1E1E;
        border-radius: 20px;
        padding: 4px 10px;
        font-size: 0.73rem;
        font-weight: 800;
        color: #1E1E1E;
        cursor: pointer;
        box-shadow: 1.5px 1.5px 0px #1E1E1E;
        transition: all 0.1s ease;
        flex-shrink: 0;
    }

    .quick-chip:hover {
        background: #FFB703;
        transform: translateY(-1px);
    }

    .quick-chip:active {
        transform: translate(1px, 1px);
        box-shadow: 0px 0px 0px #1E1E1E;
    }

    /* Footer / Input */
    .chatbot-footer {
        background: #FFFFFF;
        border-top: 3px solid #1E1E1E;
        padding: 8px 10px;
    }

    .chatbot-footer form {
        display: flex;
        gap: 8px;
        margin: 0;
    }

    #chatbotInput {
        flex: 1;
        border: 2px solid #1E1E1E;
        border-radius: 12px;
        padding: 8px 12px;
        font-size: 0.83rem;
        font-weight: 600;
        outline: none;
        background: #F9FAFB;
        transition: border-color 0.1s ease;
    }

    #chatbotInput:focus {
        border-color: #F59E0B;
        background: #FFFFFF;
    }

    .chat-send-btn {
        width: 40px;
        height: 40px;
        background: #FFB703;
        border: 2px solid #1E1E1E;
        border-radius: 12px;
        box-shadow: 2px 2px 0px #1E1E1E;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #111827;
        font-size: 0.95rem;
        transition: all 0.1s ease;
        flex-shrink: 0;
    }

    .chat-send-btn:hover {
        background: #F59E0B;
    }

    .chat-send-btn:active {
        transform: translate(1px, 1px);
        box-shadow: 0px 0px 0px #1E1E1E;
    }

    /* Responsive Mobile adjustments */
    @media (max-width: 480px) {
        #bebalung-chatbot-container {
            right: 14px;
            bottom: 80px; /* Position above bottom action or content */
        }

        .chatbot-modal {
            right: 10px;
            left: 10px;
            width: auto;
            max-width: none;
            bottom: 74px;
            height: 520px;
        }
    }
</style>

<script>
    const CHATBOT_API_URL = "{{ route('chatbot.message') }}";
    const CHATBOT_CSRF = "{{ csrf_token() }}";
    let isChatbotOpen = false;
    let chatHistory = [];

    const defaultWelcomeMessage = {
        sender: 'bot',
        text: "Halo Kak {{ $customerName ?? 'Pelanggan' }}! 👋 Selamat datang di **Depot Sate Be Ba Lung** (Meja #{{ $tableNumber ?? '01' }}).\n\nSaya **Chef Bebalung AI**, siap membantu memilihkan hidangan paling lezat & best seller dari dapur kami hari ini! Mau cari menu apa Kak?",
        items: [
            {
                id: 1,
                name: "Sate Kambing Polos",
                formatted_price: "Rp 50.000",
                price: 50000,
                image_url: "{{ asset('images/menus/sate_kambing_polos.png') }}",
                badge: "👑 Paling Best"
            },
            {
                id: 5,
                name: "Gulai Kambing",
                formatted_price: "Rp 30.000",
                price: 30000,
                image_url: "{{ asset('images/menus/gulai_kambing.png') }}",
                badge: "🔥 Best Seller"
            },
            {
                id: 12,
                name: "Es Jeruk",
                formatted_price: "Rp 10.000",
                price: 10000,
                image_url: "{{ asset('images/menus/es_jeruk.png') }}",
                badge: "⭐ Segar Favorit"
            }
        ],
        quick_replies: [
            '🔥 Menu Paling Best Seller',
            '🍢 Sate Kambing Empuk',
            '🍲 Gulai & Tongseng Rempah',
            '🥤 Minuman Segar',
            '💰 Menu Hemat / Budget',
            '🍱 Rekomendasi Paket Kombo'
        ]
    };

    function toggleChatbot() {
        const modal = document.getElementById('chatbot-modal');
        const fab = document.getElementById('chatbot-fab');
        isChatbotOpen = !isChatbotOpen;

        if (isChatbotOpen) {
            modal.style.display = 'flex';
            fab.style.display = 'none';
            if (chatHistory.length === 0) {
                chatHistory.push(defaultWelcomeMessage);
                renderChatMessages();
            }
            setTimeout(() => {
                document.getElementById('chatbotInput')?.focus();
            }, 200);
        } else {
            modal.style.display = 'none';
            fab.style.display = 'flex';
        }
    }

    function resetChat() {
        chatHistory = [defaultWelcomeMessage];
        renderChatMessages();
    }

    function renderChatMessages() {
        const container = document.getElementById('chatbotMessages');
        if (!container) return;

        container.innerHTML = '';

        chatHistory.forEach(msg => {
            const bubble = document.createElement('div');
            bubble.className = `chat-bubble ${msg.sender}`;

            // Parse simple markdown (bold, lists)
            let formattedText = msg.text
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/\n\n/g, '<br><br>')
                .replace(/\n/g, '<br>');

            let bubbleContent = `<div class="chat-bubble-text">${formattedText}</div>`;

            // If bot provided rich items
            if (msg.items && msg.items.length > 0) {
                bubbleContent += `<div class="chat-food-cards">`;
                msg.items.forEach(item => {
                    const cleanName = (item.name || '').replace(/'/g, "\\'");
                    bubbleContent += `
                        <div class="chat-food-card">
                            <img src="${item.image_url}" alt="${item.name}" class="chat-food-img" onerror="this.src='{{ asset('images/logo-goat.png') }}'">
                            <div class="chat-food-details">
                                <span class="chat-food-badge">${item.badge || 'Pilihan'}</span>
                                <h4 class="chat-food-name">${item.name}</h4>
                                <div class="chat-food-price">${item.formatted_price || 'Rp ' + Number(item.price).toLocaleString('id-ID')}</div>
                            </div>
                            <button type="button" class="chat-add-btn" onclick="addBotItemToCart(${item.id}, '${cleanName}', ${item.price})">
                                <i class="fa-solid fa-plus"></i> Tambah
                            </button>
                        </div>
                    `;
                });
                bubbleContent += `</div>`;
            }

            bubble.innerHTML = bubbleContent;
            container.appendChild(bubble);
        });

        // Update quick replies from last message
        const lastMsg = chatHistory[chatHistory.length - 1];
        renderQuickReplies(lastMsg?.quick_replies || defaultWelcomeMessage.quick_replies);

        // Scroll to bottom
        container.scrollTop = container.scrollHeight;
    }

    function renderQuickReplies(replies) {
        const container = document.getElementById('chatQuickReplies');
        if (!container) return;

        if (!replies || replies.length === 0) {
            container.style.display = 'none';
            return;
        }

        container.style.display = 'flex';
        container.innerHTML = '';

        replies.forEach(chip => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'quick-chip';
            btn.innerText = chip;
            btn.onclick = () => sendUserMessage(chip);
            container.appendChild(btn);
        });
    }

    function handleChatSubmit(e) {
        e.preventDefault();
        const input = document.getElementById('chatbotInput');
        const text = input.value.trim();
        if (!text) return;

        input.value = '';
        sendUserMessage(text);
    }

    const PROFANITY_LIST = [
        'anjing', 'anjir', 'anjay', 'asu', 'bajingan', 'bangsat', 'babi', 'kampret',
        'kontol', 'kntl', 'memek', 'mmk', 'pantek', 'puki', 'peli', 'itil', 'jembut',
        'ngentot', 'ngewe', 'titit', 'tetek', 'toket', 'lonte', 'perek', 'pelacur',
        'tai', 'taek', 'bego', 'goblok', 'tolol', 'peler', 'pepek', 'jancuk', 'jancok', 'dancuk',
        'fuck', 'bitch', 'shit', 'cunt', 'dick', 'pussy'
    ];

    function censorClientText(text) {
        let censored = text;
        PROFANITY_LIST.forEach(bw => {
            const regex = new RegExp('\\b' + bw + '\\b', 'gi');
            censored = censored.replace(regex, match => {
                const len = match.length;
                if (len <= 2) return '*'.repeat(len);
                return match[0] + '*'.repeat(len - 2) + match[len - 1];
            });
        });
        return censored;
    }

    function sendUserMessage(text) {
        // Append user message with censorship
        chatHistory.push({
            sender: 'user',
            text: censorClientText(text)
        });
        renderChatMessages();

        // Show typing indicator
        const typingEl = document.getElementById('chatTyping');
        const container = document.getElementById('chatbotMessages');
        if (typingEl) typingEl.style.display = 'flex';
        if (container) container.scrollTop = container.scrollHeight;

        // Fetch from Laravel Backend
        fetch(CHATBOT_API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CHATBOT_CSRF,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                message: text,
                table_number: '{{ $tableNumber ?? "01" }}',
                customer_name: '{{ $customerName ?? "Pelanggan" }}'
            })
        })
        .then(res => res.json())
        .then(data => {
            if (typingEl) typingEl.style.display = 'none';
            if (data.success) {
                chatHistory.push({
                    sender: 'bot',
                    text: data.reply,
                    items: data.items || [],
                    quick_replies: data.quick_replies || []
                });
            } else {
                chatHistory.push({
                    sender: 'bot',
                    text: "Mohon maaf, terjadi kendala saat menghubungkan ke database menu. Silakan coba kembali ya Kak! 🙏",
                    items: [],
                    quick_replies: defaultWelcomeMessage.quick_replies
                });
            }
            renderChatMessages();
        })
        .catch(err => {
            if (typingEl) typingEl.style.display = 'none';
            chatHistory.push({
                sender: 'bot',
                text: "Chef siap bantu! Silakan pilih salah satu opsi rekomendasi di bawah ini ya: 👇",
                items: defaultWelcomeMessage.items,
                quick_replies: defaultWelcomeMessage.quick_replies
            });
            renderChatMessages();
        });
    }

    function addBotItemToCart(id, name, price) {
        // If addToCart function exists on current page (e.g. customer/menu.blade.php)
        if (typeof window.addToCart === 'function') {
            window.addToCart(id, name, price);
        } else {
            // Fallback localStorage cart
            try {
                let savedCart = JSON.parse(localStorage.getItem('beba_cart') || '{}');
                savedCart[id] = (savedCart[id] || 0) + 1;
                localStorage.setItem('beba_cart', JSON.stringify(savedCart));
            } catch (e) {}
        }

        // Show brief visual feedback toast inside chatbot
        showChatToast(`✅ 1x ${name} ditambahkan ke pesanan!`);
    }

    function showChatToast(msg) {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #10B981;
            color: #FFFFFF;
            border: 2px solid #1E1E1E;
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 0.82rem;
            box-shadow: 3px 3px 0px #1E1E1E;
            z-index: 10002;
            animation: fadeIn 0.2s ease;
        `;
        toast.innerText = msg;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.remove();
        }, 2200);
    }
</script>
