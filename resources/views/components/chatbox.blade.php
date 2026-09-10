<!-- Chatbox AI -->
<div id="ai-chatbox" class="ai-chatbox">
    <!-- Header -->
    <div class="chatbox-header">
        <div class="chatbox-title">
            <img src="{{ asset('asset/client/images/icon_chatAI.png') }}" alt="AI Chat" class="chatbox-icon">
            <span>Tư Vấn AI</span>
        </div>
        <button class="chatbox-close" onclick="toggleChatbox()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Messages Container -->
    <div class="chatbox-messages" id="chatbox-messages">
        <div class="chat-message bot-message">
            <div class="message-content">
                Xin chào! 👋 Tôi là trợ lý AI của MinMupShop. Tôi có thể tư vấn giúp bạn về sản phẩm thời trang, chính sách, giao hàng... Bạn cần gì?
            </div>
            <span class="message-time">Bây giờ</span>
        </div>
    </div>

    <!-- Input Area -->
    <div class="chatbox-input-area">
        <form id="chat-form" onsubmit="sendMessage(event)">
            <input
                type="text"
                id="chat-input"
                class="chat-input"
                placeholder="Hỏi tôi gì đó..."
                autocomplete="off"
                required>
            <button type="submit" class="chat-send-btn" id="send-btn">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<!-- Chatbox Toggle Button (khi đóng) -->
<button id="chatbox-toggle" class="chatbox-toggle" onclick="toggleChatbox()" title="Mở chat">
    <img src="{{ asset('asset/client/images/icon_chatAI.png') }}" alt="Chat AI" class="toggle-icon">
    <span class="notification-badge">1</span>
</button>

<style>
    /* ========== CHATBOX STYLING ========== */
    .ai-chatbox {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 380px;
        height: 500px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 40px rgba(0, 0, 0, 0.16);
        display: flex;
        flex-direction: column;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        z-index: 9999;
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .chatbox-header {
        background: linear-gradient(135deg, #ff1b6b 0%, #e91e63 100%);
        color: white;
        padding: 16px;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px rgba(255, 27, 107, 0.2);
    }

    .chatbox-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 16px;
    }

    .chatbox-title i {
        font-size: 18px;
    }

    .chatbox-icon {
        width: 24px;
        height: 24px;
        object-fit: contain;
    }

    .chatbox-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .chatbox-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
    }

    .chatbox-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        background: #f8f9fa;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .chatbox-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chatbox-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .chatbox-messages::-webkit-scrollbar-thumb {
        background: #ff1b6b;
        border-radius: 3px;
    }

    .chatbox-messages::-webkit-scrollbar-thumb:hover {
        background: #e91e63;
    }

    .chat-message {
        display: flex;
        flex-direction: column;
        gap: 4px;
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-content {
        max-width: 85%;
        padding: 10px 14px;
        border-radius: 12px;
        word-wrap: break-word;
        line-height: 1.4;
        font-size: 14px;
    }

    .user-message .message-content {
        background: #ff1b6b;
        color: white;
        border-radius: 12px 2px 12px 12px;
        align-self: flex-end;
    }

    .bot-message .message-content {
        background: white;
        color: #222;
        border: 1px solid #e0e0e0;
        border-radius: 2px 12px 12px 12px;
        align-self: flex-start;
    }

    .message-time {
        font-size: 12px;
        color: #999;
        padding: 0 4px;
    }

    .user-message .message-time {
        text-align: right;
    }

    .bot-message .message-time {
        text-align: left;
    }

    .typing-indicator {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 10px 14px;
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        width: fit-content;
    }

    .typing-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ccc;
        animation: typing 1.4s infinite;
    }

    .typing-dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {

        0%,
        60%,
        100% {
            opacity: 0.5;
            transform: translateY(0);
        }

        30% {
            opacity: 1;
            transform: translateY(-10px);
        }
    }

    .chatbox-input-area {
        padding: 12px;
        border-top: 1px solid #e0e0e0;
        background: white;
        border-radius: 0 0 12px 12px;
    }

    #chat-form {
        display: flex;
        gap: 8px;
    }

    .chat-input {
        flex: 1;
        border: 1px solid #ddd;
        border-radius: 24px;
        padding: 10px 14px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s ease;
    }

    .chat-input:focus {
        border-color: #ff1b6b;
        box-shadow: 0 0 0 3px rgba(255, 27, 107, 0.1);
    }

    .chat-input::placeholder {
        color: #999;
    }

    .chat-send-btn {
        background: linear-gradient(135deg, #ff1b6b 0%, #e91e63 100%);
        color: white;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-size: 16px;
    }

    .chat-send-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(255, 27, 107, 0.3);
    }

    .chat-send-btn:active {
        transform: scale(0.95);
    }

    .chat-send-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Nút toggle khi chatbox đóng */
    .chatbox-toggle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ff1b6b 0%, #e91e63 100%);
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(255, 27, 107, 0.3);
        transition: all 0.3s ease;
        z-index: 9998;
    }

    .chatbox-toggle:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(255, 27, 107, 0.4);
    }

    .toggle-icon {
        width: 32px;
        height: 32px;
        object-fit: contain;
    }

    /* Hidden state cho toggle button */
    .chatbox-toggle.hidden {
        display: none;
    }

    .chatbox-toggle:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(255, 27, 107, 0.4);
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ff6b6b;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        border: 2px solid white;
    }

    /* Responsive */
    @media (max-width: 480px) {
        .ai-chatbox {
            width: calc(100% - 32px);
            height: 60vh;
            max-height: 500px;
        }

        .message-content {
            max-width: 90%;
        }
    }

    /* Hidden state */
    .ai-chatbox.hidden {
        display: none;
    }

    .chatbox-toggle.hidden {
        display: none;
    }

    .chatbox-toggle.show {
        display: flex;
    }
</style>

<script>
    let chatboxOpen = true;

    function toggleChatbox() {
        const chatbox = document.getElementById('ai-chatbox');
        const toggle = document.getElementById('chatbox-toggle');

        chatboxOpen = !chatboxOpen;

        if (chatboxOpen) {
            // Mở chatbox
            chatbox.classList.remove('hidden');
            toggle.classList.add('hidden');
        } else {
            // Đóng chatbox
            chatbox.classList.add('hidden');
            toggle.classList.remove('hidden');
        }
    }

    function sendMessage(e) {
        e.preventDefault();

        const input = document.getElementById('chat-input');
        const message = input.value.trim();
        const messagesContainer = document.getElementById('chatbox-messages');
        const sendBtn = document.getElementById('send-btn');

        if (!message) return;

        // Add user message
        const userMessageEl = document.createElement('div');
        userMessageEl.className = 'chat-message user-message';
        userMessageEl.innerHTML = `
      <div class="message-content">${escapeHtml(message)}</div>
      <span class="message-time">${getCurrentTime()}</span>
    `;
        messagesContainer.appendChild(userMessageEl);

        // Clear input
        input.value = '';

        // Show typing indicator
        const typingEl = document.createElement('div');
        typingEl.className = 'chat-message bot-message';
        typingEl.id = 'typing-indicator';
        typingEl.innerHTML = `
      <div class="typing-indicator">
        <div class="typing-dot"></div>
        <div class="typing-dot"></div>
        <div class="typing-dot"></div>
      </div>
    `;
        messagesContainer.appendChild(typingEl);

        // Disable send button
        sendBtn.disabled = true;

        // Scroll to bottom
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        // Send to backend
        fetch('/api/chat/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    message
                })
            })
            .then(res => res.json())
            .then(data => {
                // Remove typing indicator
                typingEl.remove();

                if (data.success) {
                    const botMessageEl = document.createElement('div');
                    botMessageEl.className = 'chat-message bot-message';
                    botMessageEl.innerHTML = `
          <div class="message-content">${escapeHtml(data.message)}</div>
          <span class="message-time">${getCurrentTime()}</span>
        `;
                    messagesContainer.appendChild(botMessageEl);
                } else {
                    const errorEl = document.createElement('div');
                    errorEl.className = 'chat-message bot-message';
                    errorEl.innerHTML = `
          <div class="message-content" style="color: #e74c3c;">⚠️ ${escapeHtml(data.message)}</div>
          <span class="message-time">${getCurrentTime()}</span>
        `;
                    messagesContainer.appendChild(errorEl);
                }

                // Scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            })
            .catch(err => {
                typingEl.remove();
                const errorEl = document.createElement('div');
                errorEl.className = 'chat-message bot-message';
                errorEl.innerHTML = `
        <div class="message-content" style="color: #e74c3c;">⚠️ Lỗi kết nối. Vui lòng thử lại.</div>
        <span class="message-time">${getCurrentTime()}</span>
      `;
                messagesContainer.appendChild(errorEl);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            })
            .finally(() => {
                sendBtn.disabled = false;
                input.focus();
            });
    }

    function getCurrentTime() {
        const now = new Date();
        return now.toLocaleTimeString('vi-VN', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Auto-scroll to bottom when messages added
    document.addEventListener('DOMContentLoaded', () => {
        const messagesContainer = document.getElementById('chatbox-messages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    });
</script>