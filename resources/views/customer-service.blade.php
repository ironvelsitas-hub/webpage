@extends('layouts.app')

@section('content')
<style>
    /* Customer Service Styles */
    .cs-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #2C1810 100%);
        padding: 60px 0;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .cs-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        background-size: cover;
        opacity: 0.3;
    }
    
    .cs-header .container {
        position: relative;
        z-index: 1;
    }
    
    .cs-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 15px;
    }
    
    .cs-header p {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    
    .contact-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
        text-align: center;
        cursor: pointer;
    }
    
    .contact-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 35px rgba(0,0,0,0.15);
    }
    
    .contact-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .contact-icon i {
        font-size: 30px;
        color: white;
    }
    
    .contact-card h4 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2C1810;
        margin-bottom: 10px;
    }
    
    .contact-card p {
        color: #666;
        margin-bottom: 15px;
    }
    
    .contact-card .contact-value {
        font-weight: 600;
        color: #C49A6C;
        font-size: 0.9rem;
    }
    
    .chat-section {
        background: #f8f9fa;
        border-radius: 20px;
        padding: 30px;
        margin-top: 30px;
    }
    
    .chat-container {
        height: 400px;
        overflow-y: auto;
        padding: 20px;
        background: white;
        border-radius: 15px;
        margin-bottom: 20px;
    }
    
    .message {
        margin-bottom: 15px;
        display: flex;
    }
    
    .message.customer {
        justify-content: flex-end;
    }
    
    .message.admin {
        justify-content: flex-start;
    }
    
    .message-bubble {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 18px;
        position: relative;
    }
    
    .message.customer .message-bubble {
        background: linear-gradient(135deg, #FF6B35, #FF8C42);
        color: white;
        border-bottom-right-radius: 5px;
    }
    
    .message.admin .message-bubble {
        background: #f1f1f1;
        color: #333;
        border-bottom-left-radius: 5px;
    }
    
    .message-time {
        font-size: 10px;
        margin-top: 5px;
        opacity: 0.7;
    }
    
    .chat-input {
        display: flex;
        gap: 10px;
    }
    
    .chat-input textarea {
        flex: 1;
        border-radius: 25px;
        padding: 12px 20px;
        border: 1px solid #ddd;
        resize: none;
    }
    
    .chat-input button {
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        border: none;
        padding: 0 25px;
        border-radius: 25px;
        color: white;
        font-weight: 600;
    }
    
    .info-box {
        background: #e8f5e9;
        border-left: 4px solid #28a745;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 20px;
    }
    
    .btn-wa {
        background: #25D366;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-wa:hover {
        background: #128C7E;
        color: white;
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .cs-header h1 {
            font-size: 1.8rem;
        }
        .contact-card {
            margin-bottom: 20px;
        }
    }
</style>

<!-- Header -->
<div class="cs-header">
    <div class="container">
        <h1><i class="fas fa-headset"></i> Customer Service 24 Jam</h1>
        <p>Kami siap membantu Anda kapan saja, di mana saja</p>
    </div>
</div>

<div class="container py-5">
    <!-- Contact Cards -->
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="contact-card" onclick="window.location.href='tel:+6281246135710'">
                <div class="contact-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h4>Call Center</h4>
                <p>Layanan telepon 24 jam</p>
                <div class="contact-value">
                    <i class="fas fa-phone"></i> +62 812-4613-5710
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="contact-card" onclick="window.open('https://wa.me/6281246135710', '_blank')">
                <div class="contact-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h4>WhatsApp</h4>
                <p>Chat langsung via WhatsApp</p>
                <div class="contact-value">
                    <i class="fab fa-whatsapp"></i> +62 812-4613-5710
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="contact-card" onclick="window.location.href='mailto:cs@kopiancol.com'">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h4>Email Support</h4>
                <p>Balasan dalam 1x24 jam</p>
                <div class="contact-value">
                    <i class="fas fa-envelope"></i> cs@kopiancol.com
                </div>
            </div>
        </div>
    </div>
    
    <!-- Social Media -->
    <div class="row mb-5">
        <div class="col-md-6 mb-3">
            <div class="contact-card" onclick="window.open('https://instagram.com/@iron_vlts', '_blank')">
                <div class="contact-icon" style="background: linear-gradient(135deg, #833ab4, #fd1d1d, #fcb045);">
                    <i class="fab fa-instagram"></i>
                </div>
                <h4>Instagram</h4>
                <p>Follow kami untuk info terbaru</p>
                <div class="contact-value">
                    <i class="fab fa-instagram"></i> @kopiancol
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="contact-card" onclick="window.open('https://facebook.com/iron', '_blank')">
                <div class="contact-icon" style="background: #1877f2;">
                    <i class="fab fa-facebook-f"></i>
                </div>
                <h4>Facebook</h4>
                <p>Ikuti kami di Facebook</p>
                <div class="contact-value">
                    <i class="fab fa-facebook"></i> Kopi Ancol
                </div>
            </div>
        </div>
    </div>
    
    <!-- Info Box -->
    <div class="info-box">
        <div class="d-flex align-items-center">
            <i class="fas fa-clock fa-2x text-success me-3"></i>
            <div>
                <strong>Layanan 24 Jam Non-Stop</strong><br>
                Customer service kami siap membantu Anda kapan saja. 
                Respon cepat dalam 5 menit untuk chat dan WhatsApp.
            </div>
        </div>
    </div>
    
    <!-- Live Chat Section -->
    <div class="chat-section">
        <h4 class="mb-3"><i class="fas fa-comments"></i> Live Chat Customer Service</h4>
        <p class="text-muted mb-3">Ada pertanyaan? Chat langsung dengan admin kami.</p>
        
        <!-- Chat Container -->
        <div class="chat-container" id="chatContainer">
            @if(isset($chats) && count($chats) > 0)
                @foreach($chats as $chat)
                <div class="message {{ $chat->sender_type }}">
                    <div class="message-bubble">
                        {{ $chat->message }}
                        <div class="message-time">
                            {{ $chat->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center text-muted py-5">
                    <i class="fas fa-comments fa-3x mb-3"></i>
                    <p>Mulai chat dengan customer service kami</p>
                    <small>Admin akan merespon dalam beberapa menit</small>
                </div>
            @endif
        </div>
        
        <!-- Chat Input -->
        <div class="chat-input">
            <textarea id="chatMessage" rows="2" placeholder="Tulis pesan Anda..."></textarea>
            <button onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i> Kirim
            </button>
        </div>
        <small class="text-muted mt-2 d-block">
            <i class="fas fa-lock"></i> Pesan Anda aman dan akan direspon oleh admin kami
        </small>
    </div>
</div>

<script>
let lastMessageId = {{ isset($chats) && count($chats) > 0 ? $chats[count($chats) - 1]->id ?? 0 : 0 }};    
    function sendMessage() {
        const message = document.getElementById('chatMessage').value.trim();
        
        if (!message) {
            alert('Silakan tulis pesan terlebih dahulu');
            return;
        }
        
        const sendBtn = event.target.closest('button');
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
        
        fetch('{{ route("customer.service.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Append message to chat
                const chatContainer = document.getElementById('chatContainer');
                const messageDiv = document.createElement('div');
                messageDiv.className = 'message customer';
                messageDiv.innerHTML = `
                    <div class="message-bubble">
                        ${escapeHtml(message)}
                        <div class="message-time">
                            ${new Date().toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'})}
                        </div>
                    </div>
                `;
                chatContainer.appendChild(messageDiv);
                chatContainer.scrollTop = chatContainer.scrollHeight;
                document.getElementById('chatMessage').value = '';
                lastMessageId = data.chat.id;
            } else {
                alert('Gagal mengirim pesan: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan, silakan coba lagi');
        })
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim';
        });
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Auto refresh chat
    setInterval(function() {
        fetch('{{ route("customer.service.new") }}?last_id=' + lastMessageId)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.messages.length > 0) {
                    const chatContainer = document.getElementById('chatContainer');
                    data.messages.forEach(msg => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = 'message ' + msg.sender_type;
                        messageDiv.innerHTML = `
                            <div class="message-bubble">
                                ${escapeHtml(msg.message)}
                                <div class="message-time">
                                    ${new Date(msg.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'})}
                                </div>
                            </div>
                        `;
                        chatContainer.appendChild(messageDiv);
                        lastMessageId = msg.id;
                    });
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }
            });
    }, 5000);
    
    // Scroll to bottom
    const chatContainer = document.getElementById('chatContainer');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
</script>
@endsection