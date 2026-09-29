<!-- Asisten Bebalung AI Floating Button & Signature Yellow Chat Window Modal -->
<div id="bebalung-chatbot-container" class="bebalung-chatbot-container">
    <!-- Floating Action Button (FAB) -->
    <div id="chatbot-fab" class="chatbot-fab" onclick="toggleChatbot()" title="Tanya Asisten Bebalung AI" role="button" tabindex="0" aria-label="Buka Chat Tanya Asisten Bebalung AI">
        <div class="fab-circle-avatar">
            <img src="{{ asset('images/logo-goat.png') }}" alt="Asisten Bebalung AI" class="fab-avatar-img">
            <span class="fab-online-dot" title="Asisten Online"></span>
        </div>
        <div class="fab-ai-badge" title="Smart AI">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
        </div>
        <div class="fab-halo"></div>
        <span class="fab-tooltip">Tanya Asisten AI ✨</span>
    </div>

    <!-- Chatbot Window Modal -->
    <div id="chatbot-modal" class="chatbot-modal" role="dialog" aria-modal="true" aria-label="Asisten Bebalung AI">
        <!-- Chatbot Header (Signature Bebalung Yellow) -->
        <div class="chatbot-header">
            <div class="chat-header-main">
                <div class="chat-header-avatar">
                    <img src="{{ asset('images/logo-goat.png') }}" alt="Asisten Bebalung">
                    <span class="header-live-dot" title="Online"></span>
                </div>
                <div class="chat-header-titles">
                    <div class="chat-header-name-row">
                        <h3 class="chat-header-title">Asisten Bebalung</h3>
                        <span class="chat-ai-pill">
                            <i class="fa-solid fa-wand-magic-sparkles"></i> Smart AI
                        </span>
                    </div>
                    <p class="chat-header-subtitle">
                        <i class="fa-solid fa-utensils"></i> Meja #<span id="chatTableNumber">{{ $tableNumber ?? '01' }}</span> &bull; <span class="text-online">● Online</span>
                    </p>
                </div>
            </div>
            <div class="chat-header-tools">
                <button type="button" class="chat-tool-btn" onclick="resetChat()" title="Mulai Ulang Percakapan">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>
                <button type="button" class="chat-tool-btn chat-tool-close" onclick="toggleChatbot()" title="Tutup Chat">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <!-- Chatbot Message Body -->
        <div class="chatbot-body" id="chatbotMessages">
            <!-- Dynamic Messages inserted here -->
        </div>

        <!-- Typing Indicator -->
        <div class="chat-typing-container" id="chatTyping" style="display: none;">
            <div class="chat-mini-avatar">
                <img src="{{ asset('images/logo-goat.png') }}" alt="Bot">
            </div>
            <div class="chat-typing-box">
                <div class="typing-dots">
                    <span></span><span></span><span></span>
                </div>
                <span class="typing-label">Asisten Bebalung sedang mengetik...</span>
            </div>
        </div>

        <!-- Quick Reply Chips -->
        <div class="chat-chips-scroll" id="chatQuickReplies">
            <!-- Dynamic chips rendered by JS -->
        </div>

        <!-- Chat Input Footer -->
        <div class="chatbot-footer">
            <form id="chatbotForm" onsubmit="handleChatSubmit(event)" class="chatbot-input-wrapper">
                <input 
                    type="text" 
                    id="chatbotInput" 
                    placeholder="Tanya menu, rekomendasi, sains, coding, atau info..." 
                    autocomplete="off"
                    aria-label="Ketik pesan untuk Asisten Bebalung AI"
                >
                <button type="submit" id="chatbotSendBtn" class="chat-send-action-btn" title="Kirim Pesan">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    /* ========================================================
       ASISTEN BEBALUNG AI - SIGNATURE YELLOW BRAND SYSTEM
       ======================================================== */
    #bebalung-chatbot-container {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        position: fixed;
        z-index: 9998;
        bottom: 24px;
        right: 20px;
        color: #111827;
        transition: bottom 0.28s cubic-bezier(0.34, 1.56, 0.64, 1), transform 0.2s ease;
    }

    #bebalung-chatbot-container.with-cart {
        bottom: 96px;
    }

    /* Floating Action Button (FAB) */
    .chatbot-fab {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FFB703 0%, #F59E0B 50%, #D97706 100%);
        border: 2.5px solid #1E1E1E;
        box-shadow: 0 8px 24px rgba(217, 119, 6, 0.45), 3px 3px 0px #1E1E1E;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        cursor: pointer;
        user-select: none;
        transition: all 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
        -webkit-tap-highlight-color: transparent;
    }

    .chatbot-fab:hover {
        transform: translateY(-3px) scale(1.06);
        box-shadow: 0 12px 28px rgba(217, 119, 6, 0.55), 4px 4px 0px #1E1E1E;
        background: linear-gradient(135deg, #FCD34D 0%, #F59E0B 50%, #B45309 100%);
    }

    .chatbot-fab:active {
        transform: translate(2px, 2px) scale(0.95);
        box-shadow: 1px 1px 0px #1E1E1E;
    }

    .fab-circle-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: #FFFFFF;
        border: 2px solid #1E1E1E;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .fab-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .fab-online-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 10px;
        height: 10px;
        background: #10B981;
        border: 2px solid #FFFFFF;
        border-radius: 50%;
        box-shadow: 0 0 4px #10B981;
    }

    .fab-ai-badge {
        position: absolute;
        top: -3px;
        right: -3px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #111827;
        color: #FCD34D;
        border: 2px solid #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.62rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.25);
    }

    .fab-halo {
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.4) 0%, transparent 70%);
        opacity: 0;
        animation: bebaHaloPulse 2.8s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes bebaHaloPulse {
        0%, 100% { transform: scale(0.9); opacity: 0.2; }
        50% { transform: scale(1.3); opacity: 0.7; }
    }

    .fab-tooltip {
        position: absolute;
        right: 68px;
        background: #111827;
        color: #FFFFFF;
        border: 2px solid #1E1E1E;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 800;
        white-space: nowrap;
        pointer-events: none;
        box-shadow: 2px 2px 0px #1E1E1E;
        opacity: 0;
        transform: translateX(8px);
        transition: all 0.2s ease;
    }

    .chatbot-fab:hover .fab-tooltip {
        opacity: 1;
        transform: translateX(0);
    }

    /* Chatbot Modal Window */
    .chatbot-modal {
        display: none;
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 390px;
        max-width: calc(100vw - 28px);
        height: 595px;
        max-height: calc(100vh - 110px);
        background: #FFFFFF;
        border: 2.5px solid #1E1E1E;
        border-radius: 22px;
        box-shadow: 5px 5px 0px #1E1E1E;
        flex-direction: column;
        overflow: hidden;
        animation: bebaSlideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 9999;
    }

    .chatbot-modal.open {
        display: flex;
    }

    @keyframes bebaSlideUp {
        from { opacity: 0; transform: translateY(20px) scale(0.96); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Signature Bebalung Yellow Header */
    .chatbot-header {
        background: linear-gradient(135deg, #FFB703 0%, #F59E0B 100%);
        color: #111827;
        padding: 13px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 2.5px solid #1E1E1E;
    }

    .chat-header-main {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chat-header-avatar {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #FFFFFF;
        border: 2px solid #1E1E1E;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 2px;
        box-shadow: 1.5px 1.5px 0px #1E1E1E;
    }

    .chat-header-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .header-live-dot {
        position: absolute;
        bottom: 0px;
        right: 0px;
        width: 9px;
        height: 9px;
        background: #10B981;
        border: 1.5px solid #1E1E1E;
        border-radius: 50%;
        box-shadow: 0 0 4px #10B981;
    }

    .chat-header-titles {
        display: flex;
        flex-direction: column;
    }

    .chat-header-name-row {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .chat-header-title {
        font-size: 1rem;
        font-weight: 900;
        color: #111827;
        letter-spacing: -0.2px;
        margin: 0;
    }

    .chat-ai-pill {
        background: #111827;
        color: #FCD34D;
        font-size: 0.62rem;
        font-weight: 900;
        padding: 2px 8px;
        border-radius: 12px;
        border: 1px solid #1E1E1E;
        letter-spacing: 0.3px;
    }

    .chat-header-subtitle {
        font-size: 0.72rem;
        color: #1F2937;
        margin: 2px 0 0 0;
        font-weight: 700;
    }

    .text-online {
        color: #047857;
        font-weight: 900;
    }

    .chat-header-tools {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .chat-tool-btn {
        background: rgba(17, 24, 39, 0.08);
        border: 1.5px solid #1E1E1E;
        color: #111827;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        cursor: pointer;
        box-shadow: 1.5px 1.5px 0px #1E1E1E;
        transition: all 0.15s;
    }

    .chat-tool-btn:hover {
        background: #111827;
        color: #FCD34D;
        transform: translateY(-1px);
    }

    .chat-tool-close:hover {
        background: #EF4444;
        color: #FFFFFF;
        border-color: #1E1E1E;
    }

    /* Chat Body */
    .chatbot-body {
        flex: 1;
        padding: 16px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 14px;
        background: #F8FAFC;
    }

    .chat-msg-row {
        display: flex;
        gap: 8px;
        align-items: flex-start;
        max-width: 94%;
    }

    .chat-msg-row.user {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .chat-msg-row.bot {
        align-self: flex-start;
        width: 100%;
        max-width: 100%;
    }

    .chat-avatar-mini {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: white;
        border: 1.5px solid #1E1E1E;
        padding: 2px;
        overflow: hidden;
        box-shadow: 1px 1px 0px #1E1E1E;
    }

    .chat-avatar-mini img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .chat-bot-content-col {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
        min-width: 0;
    }

    .chat-msg-bubble {
        padding: 10px 14px;
        border-radius: 16px;
        font-size: 0.85rem;
        line-height: 1.5;
        word-break: break-word;
    }

    .chat-msg-row.user .chat-msg-bubble {
        background: #111827;
        color: #FFFFFF;
        border: 1.5px solid #1E1E1E;
        border-bottom-right-radius: 4px;
        box-shadow: 2px 2px 0px #1E1E1E;
    }

    .chat-msg-row.bot .chat-msg-bubble {
        background: #FFFFFF;
        color: #1F2937;
        border: 1.5px solid #E2E8F0;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .chat-msg-bubble a {
        color: #EA580C;
        font-weight: 800;
        text-decoration: underline;
    }

    .chat-msg-bubble h1, .chat-msg-bubble h2, .chat-msg-bubble h3, .chat-msg-bubble h4 {
        margin: 8px 0 4px 0;
        font-weight: 900;
        color: #111827;
        line-height: 1.3;
    }

    .chat-msg-bubble h1 { font-size: 1.05rem; }
    .chat-msg-bubble h2 { font-size: 0.98rem; }
    .chat-msg-bubble h3 { font-size: 0.92rem; }
    .chat-msg-bubble h4 { font-size: 0.88rem; }

    .chat-msg-bubble p {
        margin: 0 0 6px 0;
    }
    .chat-msg-bubble p:last-child {
        margin-bottom: 0;
    }

    .chat-msg-bubble ul, .chat-msg-bubble ol {
        margin: 4px 0 6px 0;
        padding-left: 18px;
    }

    .chat-msg-bubble li {
        margin-bottom: 3px;
    }

    .chat-msg-bubble blockquote {
        border-left: 3px solid #FFB703;
        background: #F8FAFC;
        padding: 6px 10px;
        margin: 6px 0;
        border-radius: 0 6px 6px 0;
        font-style: italic;
        color: #4B5563;
    }

    .chat-msg-bubble .chat-code-card {
        background: #0F172A;
        border: 1.5px solid #1E293B;
        border-radius: 10px;
        margin: 8px 0;
        overflow: hidden;
    }

    .chat-msg-bubble .chat-code-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #1E293B;
        padding: 4px 10px;
        font-size: 0.7rem;
        color: #94A3B8;
        font-weight: 700;
        border-bottom: 1px solid #334155;
    }

    .chat-msg-bubble .chat-copy-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #E2E8F0;
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 0.68rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }

    .chat-msg-bubble .chat-copy-btn:hover {
        background: #FFB703;
        color: #111827;
        border-color: #FFB703;
    }

    .chat-msg-bubble pre {
        margin: 0;
        padding: 8px 10px;
        overflow-x: auto;
        background: transparent;
    }

    .chat-msg-bubble pre code {
        background: transparent;
        color: #F8FAFC;
        padding: 0;
        font-family: 'Fira Code', 'Consolas', monospace;
        font-size: 0.78rem;
        line-height: 1.45;
        white-space: pre;
    }

    .chat-msg-bubble code.inline-code {
        background: #F1F5F9;
        color: #DC2626;
        padding: 2px 5px;
        border-radius: 4px;
        font-family: 'Consolas', monospace;
        font-size: 0.82em;
        font-weight: 700;
        border: 1px solid #E2E8F0;
    }

    /* Signature Bebalung Yellow Interactive Menu Cards */
    .chat-menu-grid {
        display: flex;
        flex-direction: column;
        gap: 8px;
        width: 100%;
        margin-top: 2px;
    }

    .chat-menu-card {
        background: linear-gradient(135deg, #FFB703 0%, #FBBF24 50%, #F59E0B 100%);
        border: 2px solid #1E1E1E;
        border-radius: 16px;
        padding: 8px 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 2.5px 2.5px 0px #1E1E1E;
        transition: all 0.18s ease;
    }

    .chat-menu-card:hover {
        transform: translateY(-2px);
        box-shadow: 3.5px 3.5px 0px #1E1E1E;
    }

    .chat-menu-thumb {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        border: 1.5px solid #1E1E1E;
        overflow: hidden;
        flex-shrink: 0;
        background: #FFFFFF;
        box-shadow: 1px 1px 0px #1E1E1E;
    }

    .chat-menu-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .chat-menu-info {
        flex: 1;
        min-width: 0;
    }

    .chat-menu-title {
        font-size: 0.85rem;
        font-weight: 900;
        color: #111827;
        margin: 0 0 2px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-menu-price {
        font-size: 0.82rem;
        font-weight: 900;
        color: #DC2626;
    }

    .chat-menu-add-btn {
        background: #FFFFFF;
        color: #111827;
        border: 2px solid #1E1E1E;
        border-radius: 10px;
        padding: 7px 12px;
        font-size: 0.76rem;
        font-weight: 900;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        flex-shrink: 0;
        box-shadow: 2px 2px 0px #1E1E1E;
        transition: all 0.15s ease;
    }

    .chat-menu-add-btn:hover {
        background: #111827;
        color: #FCD34D;
        transform: translateY(-1px);
    }

    .chat-menu-add-btn:active {
        transform: translate(1px, 1px);
        box-shadow: 0px 0px 0px #1E1E1E;
    }

    .chat-menu-add-btn.added {
        background: #10B981;
        color: #FFFFFF;
        border-color: #1E1E1E;
    }

    /* Typing Indicator */
    .chat-typing-container {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0 16px 8px;
        background: #F8FAFC;
    }

    .chat-mini-avatar {
        width: 26px;
        height: 26px;
        border-radius: 8px;
        background: white;
        border: 1.5px solid #1E1E1E;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        padding: 2px;
    }

    .chat-typing-box {
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 12px;
        padding: 6px 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.72rem;
        color: #6B7280;
        font-weight: 700;
    }

    .typing-dots {
        display: flex;
        gap: 4px;
    }

    .typing-dots span {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #FFB703;
        border: 1px solid #1E1E1E;
        animation: dotPulse 1.2s infinite ease-in-out;
    }

    .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
    .typing-dots span:nth-child(3) { animation-delay: 0.4s; }

    @keyframes dotPulse {
        0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
        40% { transform: scale(1.2); opacity: 1; }
    }

    /* Quick Reply Chips */
    .chat-chips-scroll {
        display: flex;
        gap: 6px;
        padding: 8px 14px;
        background: #F1F5F9;
        border-top: 2px solid #E2E8F0;
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }

    .chat-chip {
        background: #FFFFFF;
        color: #1F2937;
        border: 1.5px solid #1E1E1E;
        border-radius: 14px;
        padding: 5px 12px;
        font-size: 0.72rem;
        font-weight: 800;
        cursor: pointer;
        flex-shrink: 0;
        box-shadow: 1.5px 1.5px 0px #1E1E1E;
        transition: all 0.15s;
    }

    .chat-chip:hover {
        background: #FFB703;
        color: #111827;
        transform: translateY(-1px);
    }

    .chat-chip:active {
        transform: translate(1px, 1px);
        box-shadow: 0px 0px 0px #1E1E1E;
    }

    /* Input Footer */
    .chatbot-footer {
        padding: 12px 14px;
        background: #FFFFFF;
        border-top: 2px solid #E2E8F0;
    }

    .chatbot-input-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #F8FAFC;
        border: 2px solid #1E1E1E;
        border-radius: 14px;
        padding: 4px 6px 4px 14px;
        box-shadow: 2px 2px 0px #1E1E1E;
        transition: all 0.15s;
    }

    .chatbot-input-wrapper:focus-within {
        border-color: #EA580C;
        box-shadow: 3px 3px 0px #EA580C;
        background: #FFFFFF;
    }

    .chatbot-input-wrapper input {
        flex: 1;
        border: none;
        outline: none;
        background: transparent;
        font-size: 0.85rem;
        font-weight: 700;
        color: #111827;
    }

    .chat-send-action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #FFB703;
        color: #111827;
        border: 2px solid #1E1E1E;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        cursor: pointer;
        box-shadow: 2px 2px 0px #1E1E1E;
        transition: all 0.15s;
    }

    .chat-send-action-btn:hover {
        background: #F59E0B;
        transform: scale(1.05);
    }

    .chat-send-action-btn:active {
        transform: translate(1px, 1px);
        box-shadow: 0px 0px 0px #1E1E1E;
    }

    @media (max-width: 480px) {
        .chatbot-modal {
            width: calc(100vw - 20px);
            right: 10px;
            bottom: 75px;
            height: 520px;
        }
    }
</style>

<script>
    let chatHistory = [];
    const CHAT_STORAGE_KEY = 'beba_asisten_chat_history_v5';

    function toggleChatbot() {
        const modal = document.getElementById('chatbot-modal');
        if (modal) {
            modal.classList.toggle('open');
            if (modal.classList.contains('open')) {
                if (chatHistory.length === 0) {
                    initBebalungChat();
                }
                setTimeout(() => {
                    document.getElementById('chatbotInput')?.focus();
                    scrollChatToBottom();
                }, 100);
            }
        }
    }

    function initBebalungChat() {
        try {
            const saved = localStorage.getItem(CHAT_STORAGE_KEY);
            if (saved) {
                chatHistory = JSON.parse(saved);
                renderChatHistory();
                return;
            }
        } catch (e) {}

        sendChatToBackend('');
    }

    function resetChat() {
        chatHistory = [];
        try {
            localStorage.removeItem(CHAT_STORAGE_KEY);
        } catch (e) {}
        document.getElementById('chatbotMessages').innerHTML = '';
        sendChatToBackend('');
    }

    function handleChatSubmit(e) {
        e.preventDefault();
        const input = document.getElementById('chatbotInput');
        const text = input.value.trim();
        if (!text) return;

        input.value = '';
        appendChatMessage('user', text);
        sendChatToBackend(text);
    }

    function sendQuickReply(text) {
        appendChatMessage('user', text);
        sendChatToBackend(text);
    }

    function appendChatMessage(sender, text, items = []) {
        chatHistory.push({ sender, text, items: items || [], time: new Date().toISOString() });
        saveChatHistory();
        renderSingleMessage(sender, text, items);
        scrollChatToBottom();
    }

    function saveChatHistory() {
        try {
            localStorage.setItem(CHAT_STORAGE_KEY, JSON.stringify(chatHistory));
        } catch (e) {}
    }

    function renderChatHistory() {
        const container = document.getElementById('chatbotMessages');
        container.innerHTML = '';
        chatHistory.forEach(msg => {
            renderSingleMessage(msg.sender, msg.text, msg.items || []);
        });
        scrollChatToBottom();
    }

    function renderSingleMessage(sender, text, items = []) {
        const container = document.getElementById('chatbotMessages');
        const row = document.createElement('div');
        row.className = `chat-msg-row ${sender}`;

        let formattedText = formatMarkdown(text);

        if (sender === 'bot') {
            let itemsHtml = '';
            if (items && items.length > 0) {
                itemsHtml = `
                    <div class="chat-menu-grid">
                        ${items.map(item => `
                            <div class="chat-menu-card">
                                <div class="chat-menu-thumb">
                                    <img src="${item.image_url || '/images/logo-goat.png'}" alt="${escapeHtml(item.name)}" onerror="this.src='/images/logo-goat.png'">
                                </div>
                                <div class="chat-menu-info">
                                    <div class="chat-menu-title">${escapeHtml(item.name)}</div>
                                    <div class="chat-menu-price">${item.formatted_price || ('Rp ' + (item.price || 0).toLocaleString('id-ID'))}</div>
                                </div>
                                <button type="button" class="chat-menu-add-btn" onclick="addFromChat(this, ${item.id}, '${escapeHtml(item.name)}', ${item.price})" title="Tambah ke Pesanan Meja #{{ $tableNumber ?? '01' }}">
                                    <i class="fa-solid fa-plus"></i> Tambah
                                </button>
                            </div>
                        `).join('')}
                    </div>
                `;
            }

            row.innerHTML = `
                <div class="chat-avatar-mini">
                    <img src="{{ asset('images/logo-goat.png') }}" alt="Asisten">
                </div>
                <div class="chat-bot-content-col">
                    <div class="chat-msg-bubble">${formattedText}</div>
                    ${itemsHtml}
                </div>
            `;
        } else {
            row.innerHTML = `
                <div class="chat-msg-bubble">${escapeHtml(text)}</div>
            `;
        }

        container.appendChild(row);
    }

    // Action button to add items directly to cart from chatbot
    function addFromChat(btn, id, name, price) {
        if (typeof window.addToCart === 'function') {
            window.addToCart(id, name, price);
        } else {
            try {
                let currentCart = JSON.parse(localStorage.getItem('beba_cart_{{ $tableNumber ?? '01' }}') || '{}');
                currentCart[id] = (currentCart[id] || 0) + 1;
                localStorage.setItem('beba_cart_{{ $tableNumber ?? '01' }}', JSON.stringify(currentCart));
            } catch(e){}
        }

        // Visual feedback on button
        const originalHtml = btn.innerHTML;
        btn.classList.add('added');
        btn.innerHTML = '<i class="fa-solid fa-check"></i> +1 Masuk';
        setTimeout(() => {
            btn.classList.remove('added');
            btn.innerHTML = originalHtml;
        }, 1200);
    }
    window.addFromChat = addFromChat;

    function copyCode(btn) {
        const card = btn.closest('.chat-code-card');
        const codeElem = card ? card.querySelector('code') : null;
        if (codeElem) {
            const codeText = codeElem.innerText;
            navigator.clipboard.writeText(codeText).then(() => {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Tersalin';
                setTimeout(() => { btn.innerHTML = originalHtml; }, 1500);
            }).catch(() => {
                btn.innerText = 'Gagal';
            });
        }
    }
    window.copyCode = copyCode;

    function formatMarkdown(text) {
        if (!text) return '';
        
        // 1. Extract and protect code blocks
        const codeBlocks = [];
        let src = text.replace(/```([a-zA-Z0-9_\-\+]*)\s*\n([\s\S]*?)```/g, function(match, lang, code) {
            const placeholder = `___CODE_BLOCK_${codeBlocks.length}___`;
            codeBlocks.push({
                lang: lang || 'code',
                code: escapeHtml(code.replace(/^\n+|\n+$/g, ''))
            });
            return placeholder;
        });

        // 2. Escape standard HTML
        let escaped = escapeHtml(src);

        // 3. Headers
        escaped = escaped.replace(/^### (.*$)/gim, '<h3>$1</h3>');
        escaped = escaped.replace(/^## (.*$)/gim, '<h2>$1</h2>');
        escaped = escaped.replace(/^# (.*$)/gim, '<h1>$1</h1>');

        // 4. Blockquotes
        escaped = escaped.replace(/^\> (.*$)/gim, '<blockquote>$1</blockquote>');

        // 5. Bold & Italic
        escaped = escaped.replace(/\*\*\*(.*?)\*\*\*/g, '<strong><em>$1</em></strong>');
        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        escaped = escaped.replace(/\*(.*?)\*/g, '<em>$1</em>');
        escaped = escaped.replace(/_([^_]+)_/g, '<em>$1</em>');

        // 6. Inline Code
        escaped = escaped.replace(/`([^`]+)`/g, '<code class="inline-code">$1</code>');

        // 7. Markdown Links
        escaped = escaped.replace(/\[(.*?)\]\((https?:\/\/[^\s]+)\)/g, '<a href="$2" target="_blank" rel="noopener">$1</a>');

        // 8. Unordered list items (- or *)
        escaped = escaped.replace(/^[•\-\*]\s+(.*$)/gim, '<li>$1</li>');
        escaped = escaped.replace(/(<li>.*<\/li>)/gim, '<ul>$1</ul>');
        escaped = escaped.replace(/<\/ul>\s*<ul>/gim, '');

        // 9. Line breaks outside tags
        escaped = escaped.replace(/\n/g, '<br>');
        // Clean up excessive br tags around headers/blocks
        escaped = escaped.replace(/<br><(h[1-3]|blockquote|ul|div)/gi, '<$1');
        escaped = escaped.replace(/<\/(h[1-3]|blockquote|ul|div)><br>/gi, '</$1>');

        // 10. Restore Code Blocks
        codeBlocks.forEach((item, i) => {
            const placeholder = `___CODE_BLOCK_${i}___`;
            const codeCardHtml = `
                <div class="chat-code-card">
                    <div class="chat-code-header">
                        <span><i class="fa-solid fa-code"></i> ${item.lang}</span>
                        <button type="button" class="chat-copy-btn" onclick="copyCode(this)">
                            <i class="fa-regular fa-copy"></i> Salin Kode
                        </button>
                    </div>
                    <pre><code>${item.code}</code></pre>
                </div>
            `;
            escaped = escaped.replace(placeholder, codeCardHtml);
        });

        return escaped;
    }

    function escapeHtml(string) {
        return String(string)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function scrollChatToBottom() {
        const body = document.getElementById('chatbotMessages');
        if (body) {
            body.scrollTop = body.scrollHeight;
        }
    }

    function sendChatToBackend(message) {
        const typing = document.getElementById('chatTyping');
        if (typing) typing.style.display = 'flex';

        const payload = {
            message: message,
            history: chatHistory.slice(-12),
            table_number: "{{ $tableNumber ?? '01' }}",
            customer_name: "{{ $customerName ?? '' }}"
        };

        fetch("{{ route('chatbot.message') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(res => {
            if (!res.ok) throw new Error('Route HTTP ' + res.status);
            return res.json();
        })
        .then(data => {
            if (typing) typing.style.display = 'none';
            if (data.reply) {
                appendChatMessage('bot', data.reply, data.items || []);
            }
            if (data.quick_replies && data.quick_replies.length > 0) {
                renderQuickReplies(data.quick_replies);
            }
        })
        .catch(err => {
            // Fallback langsung ke endpoint standalone /chat.php (Gemini AI Direct)
            fetch('/chat.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(fallbackData => {
                if (typing) typing.style.display = 'none';
                if (fallbackData.reply) {
                    appendChatMessage('bot', fallbackData.reply, fallbackData.items || []);
                }
                if (fallbackData.quick_replies && fallbackData.quick_replies.length > 0) {
                    renderQuickReplies(fallbackData.quick_replies);
                }
            })
            .catch(finalErr => {
                if (typing) typing.style.display = 'none';
                appendChatMessage('bot', "Halo Kak! Maaf, koneksi sedang sibuk. Silakan coba kirim pesan ulang ya! ✨");
            });
        });
    }

    function renderQuickReplies(replies) {
        const container = document.getElementById('chatQuickReplies');
        if (!container) return;
        container.innerHTML = '';
        replies.forEach(r => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'chat-chip';
            btn.innerText = r;
            btn.onclick = () => sendQuickReply(r);
            container.appendChild(btn);
        });
    }
</script>
