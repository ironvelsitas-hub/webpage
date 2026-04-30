<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Customer: Lihat chat dengan admin
     */
    public function customerIndex()
    {
        $userId = Auth::id();
        
        // Ambil semua chat user
        $chats = ChatMessage::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Ambil order yang bisa dikomplain
        $orders = Order::where('customer_email', Auth::user()->email)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('customer.chat', compact('chats', 'orders'));
    }
    
    /**
     * Customer: Kirim pesan (dengan dukungan gambar) - TANPA TEKS "Mengirim gambar"
     */
    public function customerSend(Request $request)
    {
        try {
            $request->validate([
                'message' => 'nullable|string|max:1000',
                'order_id' => 'nullable|exists:orders,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Validasi minimal salah satu (pesan atau gambar)
            if (empty($request->message) && !$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan tulis pesan atau pilih gambar'
                ], 400);
            }

            $imageUrl = null;
            
            // Upload image if exists
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('chat_images', $filename, 'public');
                $imageUrl = Storage::url($path);
            }
            
            // PERBAIKAN: Jika hanya gambar (tanpa teks), message tetap NULL
            // Jangan isi dengan teks "Mengirim gambar"
            $message = $request->message;
            
            $chat = ChatMessage::create([
                'user_id' => Auth::id(),
                'order_id' => $request->order_id,
                'message' => $message, // Bisa NULL jika hanya gambar
                'image_url' => $imageUrl,
                'sender_type' => 'customer',
                'status' => 'sent',
                'is_read' => false
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pesan terkirim',
                'chat' => $chat
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Admin: Kirim balasan (dengan dukungan gambar) - TANPA TEKS "Mengirim gambar"
     */
    public function adminReply(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'message' => 'nullable|string|max:1000',
                'order_id' => 'nullable|exists:orders,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Validasi minimal salah satu (pesan atau gambar)
            if (empty($request->message) && !$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan tulis pesan atau pilih gambar'
                ], 400);
            }

            $imageUrl = null;
            
            // Upload image if exists
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('chat_images', $filename, 'public');
                $imageUrl = Storage::url($path);
            }
            
            // PERBAIKAN: Jika hanya gambar (tanpa teks), message tetap NULL
            $message = $request->message;
            
            $chat = ChatMessage::create([
                'user_id' => $request->user_id,
                'order_id' => $request->order_id,
                'message' => $message, // Bisa NULL jika hanya gambar
                'image_url' => $imageUrl,
                'sender_type' => 'admin',
                'status' => 'replied',
                'is_read' => true,
                'read_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Balasan terkirim',
                'chat' => $chat
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Admin: Lihat semua chat
     */
    public function adminIndex()
    {
        // Ambil semua user yang pernah chat
        $users = User::whereHas('chatMessages')->get();
        
        $conversations = [];
        
        foreach ($users as $user) {
            $lastMessage = ChatMessage::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            $unreadCount = ChatMessage::where('user_id', $user->id)
                ->where('sender_type', 'customer')
                ->where('is_read', false)
                ->count();
            
            $conversations[] = [
                'user' => $user,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount
            ];
        }
        
        // Sort by last message time
        usort($conversations, function($a, $b) {
            return $b['last_message']->created_at <=> $a['last_message']->created_at;
        });
        
        $unreadCount = ChatMessage::where('sender_type', 'customer')
            ->where('is_read', false)
            ->count();
        
        return view('admin.chat.index', compact('conversations', 'unreadCount'));
    }
    
    /**
     * Admin: Lihat detail chat dengan customer
     */
    public function adminShow($userId)
    {
        $customer = User::findOrFail($userId);
        
        // Ambil semua chat dengan customer ini
        $chats = ChatMessage::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark as read
        ChatMessage::where('user_id', $userId)
            ->where('sender_type', 'customer')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
                'status' => 'read'
            ]);
        
        return view('admin.chat.show', compact('customer', 'chats'));
        
    }
    
    /**
     * Get new messages for AJAX polling
     */
    public function getNewMessages(Request $request)
    {
        $lastId = $request->last_id ?? 0;
        
        if (Auth::user() && Auth::user()->role == 'admin' && $request->user_id) {
            // Admin melihat chat dengan customer tertentu
            $messages = ChatMessage::where('user_id', $request->user_id)
                ->where('id', '>', $lastId)
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            // Customer melihat chat sendiri
            $messages = ChatMessage::where('user_id', Auth::id())
                ->where('id', '>', $lastId)
                ->orderBy('created_at', 'asc')
                ->get();
        }
        
        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }
    
    /**
     * Get unread messages count (AJAX)
     */
    public function getUnreadCount(Request $request)
    {
        if (Auth::user() && Auth::user()->role == 'admin') {
            $count = ChatMessage::where('sender_type', 'customer')
                ->where('is_read', false)
                ->count();
        } else {
            $count = ChatMessage::where('user_id', Auth::id())
                ->where('sender_type', 'admin')
                ->where('is_read', false)
                ->count();
        }
        
        return response()->json(['count' => $count]);
    }
}