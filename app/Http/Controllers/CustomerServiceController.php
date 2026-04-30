<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerServiceController extends Controller
{
    /**
     * Menampilkan halaman customer service
     */
    public function index()
    {
        // Jika user login, ambil riwayat chat
        $chats = [];
        if (Auth::check()) {
            $chats = ChatMessage::where('user_id', Auth::id())
                ->where('sender_type', 'customer')
                ->orWhere(function($query) {
                    $query->where('sender_type', 'admin')
                          ->where('user_id', Auth::id());
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }
        
        return view('customer-service', compact('chats'));
    }
    
    /**
     * Kirim pesan customer service
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|min:1|max:1000',
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100'
        ]);
        
        $chat = ChatMessage::create([
            'user_id' => Auth::id() ?? null,
            'customer_name' => $request->name ?? (Auth::user()->name ?? 'Guest'),
            'customer_email' => $request->email ?? (Auth::user()->email ?? null),
            'message' => $request->message,
            'sender_type' => 'customer',
            'status' => 'sent',
            'is_read' => false
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Pesan terkirim, admin akan segera merespon.',
            'chat' => $chat
        ]);
    }
    
    /**
     * Get contact info
     */
    public function getContactInfo()
    {
        $contactInfo = [
            'phone' => '+62 812-4613-5710',
            'whatsapp' => '6281246135710',
            'email' => 'cs@kopiancol.com',
            'instagram' => '@kopiancol',
            'facebook' => 'KopiAncol',
            'address' => 'Jl. Ruteng-Elar, Colol, Manggarai Timur, Nusa Tenggara Timur'
        ];
        
        return response()->json($contactInfo);
    }
}