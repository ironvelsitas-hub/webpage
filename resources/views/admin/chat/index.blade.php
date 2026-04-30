@extends('layouts.admin')

@section('content')
<style>
    .chat-list {
        max-height: 600px;
        overflow-y: auto;
    }
    .chat-item {
        padding: 15px;
        border-bottom: 1px solid #e0e0e0;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .chat-item:hover {
        background: #FFF8F0;
    }
    .chat-item.unread {
        background: #FFF3E0;
        border-left: 3px solid #FF6B35;
    }
    .chat-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #FF6B35, #FF8C42);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
    }
    .unread-badge {
        background: #FF6B35;
        color: white;
        border-radius: 20px;
        padding: 2px 8px;
        font-size: 11px;
    }
</style>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-comments" style="color: #FF6B35;"></i> Chat Customer
        @if($unreadCount > 0)
            <span class="badge bg-danger">{{ $unreadCount }}</span>
        @endif
    </h1>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Percakapan</h5>
    </div>
    <div class="card-body p-0">
        <div class="chat-list">
            @forelse($conversations as $conv)
            <a href="{{ route('admin.chat.show', $conv['user']->id) }}" class="text-decoration-none">
                <div class="chat-item {{ $conv['unread_count'] > 0 ? 'unread' : '' }}">
                    <div class="d-flex align-items-center gap-3">
                        <div class="chat-avatar">
                            {{ substr($conv['user']->name, 0, 1) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ $conv['user']->name }}</h6>
                                <small class="text-muted">{{ $conv['last_message']->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <small class="text-muted">{{ Str::limit($conv['last_message']->message, 50) }}</small>
                                @if($conv['unread_count'] > 0)
                                    <span class="unread-badge">{{ $conv['unread_count'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                <p class="text-muted">Belum ada percakapan dengan customer</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection