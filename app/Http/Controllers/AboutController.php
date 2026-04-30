<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Menampilkan halaman Tentang Kami
     */
    public function index()
    {
        // Data statistik untuk ditampilkan
        $totalProducts = Product::count();
        $totalBranches = Branch::where('is_active', true)->count();
        $totalCustomers = Order::distinct('customer_email')->count('customer_email');
        $totalOrders = Order::count();
        
        // Data tim/karyawan (bisa diambil dari database atau hardcoded)
        $team = [
            [
                'name' => 'Iron',
                'position' => 'Founder & Head Barista',
                'description' => 'Pakar kopi dengan pengalaman 10+ tahun di industri kopi.',
                'image' => null,
                'social' => ['instagram' => '#', 'linkedin' => '#']
            ],
            [
                'name' => 'Maria',
                'position' => 'Master Roaster',
                'description' => 'Spesialis roasting dengan sertifikasi internasional.',
                'image' => null,
                'social' => ['instagram' => '#', 'linkedin' => '#']
            ],
            [
                'name' => 'Budi',
                'position' => 'Operations Manager',
                'description' => 'Memastikan setiap cangkir kopi berkualitas tinggi.',
                'image' => null,
                'social' => ['instagram' => '#', 'linkedin' => '#']
            ],
            [
                'name' => 'Susi',
                'position' => 'Customer Experience',
                'description' => 'Menciptakan pengalaman terbaik untuk pelanggan.',
                'image' => null,
                'social' => ['instagram' => '#', 'linkedin' => '#']
            ]
        ];
        
        // Nilai-nilai perusahaan
        $values = [
            [
                'icon' => 'fa-seedling',
                'title' => 'Kualitas Terbaik',
                'description' => 'Hanya biji kopi pilihan yang kami gunakan.'
            ],
            [
                'icon' => 'fa-hand-holding-heart',
                'title' => 'Fair Trade',
                'description' => 'Mendukung petani kopi lokal dengan harga adil.'
            ],
            [
                'icon' => 'fa-leaf',
                'title' => 'Ramah Lingkungan',
                'description' => 'Komitmen terhadap keberlanjutan lingkungan.'
            ],
            [
                'icon' => 'fa-users',
                'title' => 'Komunitas',
                'description' => 'Membangun komunitas pecinta kopi yang solid.'
            ]
        ];
        
        return view('about.index', compact('totalProducts', 'totalBranches', 'totalCustomers', 'totalOrders', 'team', 'values'));
    }
}