@extends('layouts.admin')

@section('content')
<style>
    .chat-container {
        height: 500px;
        overflow-y: auto;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
    }
    .message {
        margin-bottom: 15px;
        display: flex;
    }
    .message.customer {
        justify-content: flex-start;
    }
    .message.admin {
        justify-content: flex-end;
    }
    .message-bubble {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 18px;
        position: relative;
    }
    .message.customer .message-bubble {
        background: white;
        color: #333;
        border: 1px solid #ddd;
        border-bottom-left-radius: 5px;
    }
    .message.admin .message-bubble {
        background: linear-gradient(135deg, #FF6B35, #FF8C42);
        color: white;
        border-bottom-right-radius: 5px;
    }
    .message-time {
        font-size: 10px;
        margin-top: 5px;
        opacity: 0.7;
    }
    .message-image {
        max-width: 200px;
        max-height: 200px;
        border-radius: 10px;
        margin-top: 5px;
        cursor: pointer;
    }
    .chat-input-area {
        background: white;
        padding: 15px;
        border-radius: 10px;
        margin-top: 15px;
        border: 1px solid #ddd;
    }
    .image-preview {
        margin-top: 10px;
        display: none;
        position: relative;
        display: inline-block;
    }
    .image-preview img {
        max-width: 100px;
        max-height: 100px;
        border-radius: 10px;
        border: 2px solid #FF6B35;
    }
    .remove-image {
        position: absolute;
        top: -8px;
        right: -8px;
        background: red;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
    }
    .file-upload-btn {
        background: #6c757d;
        border: none;
        padding: 8px 15px;
        border-radius: 8px;
        color: white;
        cursor: pointer;
    }
    /* Modal untuk preview gambar */
    .image-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        z-index: 9999;
        cursor: pointer;
        align-items: center;
        justify-content: center;
    }
    .image-modal img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }
    .image-modal.active {
        display: flex;
    }
    .input-group-custom {
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }
    .input-group-custom textarea {
        flex: 1;
    }
</style>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-comments" style="color: #FF6B35;"></i> Chat dengan {{ $customer->name }}
    </h1>
    <a href="{{ route('admin.chat.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <!-- Customer Info -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="alert alert-info">
                    <strong>Nama:</strong> {{ $customer->name }}<br>
                    <strong>Email:</strong> {{ $customer->email }}<br>
                    <strong>Telepon:</strong> {{ $customer->phone ?? '-' }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-secondary">
                    <strong>Bergabung:</strong> {{ $customer->created_at->format('d/m/Y') }}<br>
                    <strong>Total Pesanan:</strong> {{ \App\Models\Order::where('customer_email', $customer->email)->count() }}
                </div>
            </div>
        </div>
        
        <!-- Chat Messages -->
        <div class="chat-container" id="chatContainer">
            @foreach($chats as $chat)
            <div class="message {{ $chat->sender_type }}">
                <div class="message-bubble">
                    @if($chat->message)
                        <div>{{ $chat->message }}</div>
                    @endif
                    @if($chat->image_url)
                        <img src="{{ asset($chat->image_url) }}" 
                             class="message-image" 
                             onclick="openImageModal('{{ asset($chat->image_url) }}')" 
                             alt="Image"
                             style="max-width: 200px; max-height: 200px; border-radius: 10px; cursor: pointer;">
                    @endif
                    <div class="message-time">
                        {{ $chat->created_at->format('H:i') }}
                        @if($chat->sender_type == 'customer')
                            @if($chat->is_read)
                                <span class="text-success">✓✓ Dibaca</span>
                            @else
                                <span class="text-muted">✓ Terkirim</span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Chat Input -->
        <div class="chat-input-area">
            <div class="input-group-custom">
                <textarea id="messageInput" class="form-control" rows="2" placeholder="Tulis balasan..."></textarea>
                <div>
                    <button class="file-upload-btn" id="imageUploadBtn" title="Upload Gambar">
                        <i class="fas fa-image"></i>
                    </button>
                    <input type="file" id="imageInput" accept="image/*" style="display: none;">
                </div>
                <button class="btn btn-primary" id="sendBtn">
                    <i class="fas fa-paper-plane"></i> Kirim
                </button>
            </div>
            
            <!-- Preview gambar yang akan diupload -->
            <div id="imagePreviewContainer" class="image-preview" style="display: none;">
                <img id="imagePreview" src="">
                <div class="remove-image" onclick="removeImage()">×</div>
            </div>
            
            <small class="text-muted">Balasan Anda akan langsung terkirim ke customer.</small>
        </div>
    </div>
</div>

<!-- Modal untuk preview gambar -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <img id="modalImage" src="">
</div>

<script>
    let lastMessageId = {{ $chats->last()->id ?? 0 }};
    let selectedImage = null;
    
    // Upload image button
    document.getElementById('imageUploadBtn').addEventListener('click', function() {
        document.getElementById('imageInput').click();
    });
    
    // Handle image selection
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran gambar maksimal 2MB');
                return;
            }
            
            selectedImage = file;
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('imagePreview');
                const container = document.getElementById('imagePreviewContainer');
                preview.src = event.target.result;
                container.style.display = 'inline-block';
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Remove selected image
    function removeImage() {
        selectedImage = null;
        document.getElementById('imageInput').value = '';
        document.getElementById('imagePreviewContainer').style.display = 'none';
        document.getElementById('imagePreview').src = '';
    }
    
    // Kirim pesan
    document.getElementById('sendBtn').addEventListener('click', function() {
        const message = document.getElementById('messageInput').value.trim();
        
        if (!message && !selectedImage) {
            alert('Silakan tulis pesan atau pilih gambar');
            return;
        }
        
        const formData = new FormData();
        formData.append('user_id', '{{ $customer->id }}');
        formData.append('message', message);
        if (selectedImage) {
            formData.append('image', selectedImage);
        }
        
        const sendBtn = document.getElementById('sendBtn');
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
        
        fetch('{{ route("admin.chat.reply") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const chatContainer = document.getElementById('chatContainer');
                const messageDiv = document.createElement('div');
                messageDiv.className = 'message admin';
                
                let contentHtml = `<div class="message-bubble">`;
                if (data.chat.message) {
                    contentHtml += `<div>${escapeHtml(data.chat.message)}</div>`;
                }
                if (data.chat.image_url) {
                    contentHtml += `<img src="${data.chat.image_url}" class="message-image" onclick="openImageModal('${data.chat.image_url}')" alt="Image" style="max-width: 200px; max-height: 200px; border-radius: 10px; cursor: pointer;">`;
                }
                contentHtml += `
                        <div class="message-time">
                            ${new Date().toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'})}
                        </div>
                    </div>
                `;
                messageDiv.innerHTML = contentHtml;
                chatContainer.appendChild(messageDiv);
                chatContainer.scrollTop = chatContainer.scrollHeight;
                
                document.getElementById('messageInput').value = '';
                removeImage();
                lastMessageId = data.chat.id;
            } else {
                alert('Gagal: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        })
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim';
        });
    });
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function openImageModal(imageUrl) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modalImg.src = imageUrl;
        modal.classList.add('active');
    }
    
    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.remove('active');
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
    
    setInterval(function() {
        fetch('{{ route("admin.chat.new") }}?last_id=' + lastMessageId + '&user_id={{ $customer->id }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.messages.length > 0) {
                    const chatContainer = document.getElementById('chatContainer');
                    data.messages.forEach(msg => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = 'message ' + msg.sender_type;
                        
                        let contentHtml = `<div class="message-bubble">`;
                        if (msg.message) {
                            contentHtml += `<div>${escapeHtml(msg.message)}</div>`;
                        }
                        if (msg.image_url) {
                            contentHtml += `<img src="${msg.image_url}" class="message-image" onclick="openImageModal('${msg.image_url}')" alt="Image" style="max-width: 200px; max-height: 200px; border-radius: 10px; cursor: pointer;">`;
                        }
                        contentHtml += `
                                <div class="message-time">
                                    ${new Date(msg.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'})}
                                </div>
                            </div>
                        `;
                        messageDiv.innerHTML = contentHtml;
                        chatContainer.appendChild(messageDiv);
                        lastMessageId = msg.id;
                    });
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }
            });
    }, 3000);
    
    const chatContainer = document.getElementById('chatContainer');
    chatContainer.scrollTop = chatContainer.scrollHeight;
</script>
@endsection