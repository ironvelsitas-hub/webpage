<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Constructor - require auth
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Customer Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $orders = Order::where('customer_email', $user->email)->orderBy('created_at', 'desc')->take(5)->get();
        $totalOrders = Order::where('customer_email', $user->email)->count();
        $pendingOrders = Order::where('customer_email', $user->email)->where('status', 'pending')->count();
        $completedOrders = Order::where('customer_email', $user->email)->where('status', 'completed')->count();
        
        return view('customer.dashboard', compact('user', 'orders', 'totalOrders', 'pendingOrders', 'completedOrders'));
    }
    
    /**
     * Customer Orders History
     */
    public function orders()
    {
        $orders = Order::where('customer_email', Auth::user()->email)->orderBy('created_at', 'desc')->get();
        return view('customer.orders', compact('orders'));
    }
    
    /**
     * Customer Profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }
    
    /**
     * Update Customer Profile
     */
    public function updateProfile(Request $request)
{
    $request->validate([
        'phone' => 'required|string|min:10|max:15',
        'address' => 'required|string|min:10',
    ]);
    
    $user = auth()->user();
    $user->phone = $request->phone;
    $user->address = $request->address;
    $user->save();
    
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'phone' => $user->phone,
            'address' => $user->address,
            'message' => 'Profil berhasil diperbarui'
        ]);
    }
    
    return redirect()->back()->with('success', 'Profil berhasil diperbarui');
}
    
    /**
     * Update Customer Password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Password saat ini salah');
        }
        
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return redirect()->back()->with('success', 'Password berhasil diubah');
    }
}