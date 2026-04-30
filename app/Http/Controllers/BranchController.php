<?php
// app/Http/Controllers/BranchController.php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::where('is_active', true)
            ->orderBy('order_position')
            ->get();
        
        // Ambil cabang utama untuk map default
        $mainBranch = $branches->first();
        
        return view('locations.index', compact('branches', 'mainBranch'));
    }

    public function show($slug)
    {
        $branch = Branch::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        
        $otherBranches = Branch::where('is_active', true)
            ->where('id', '!=', $branch->id)
            ->orderBy('order_position')
            ->get();
        
        return view('locations.show', compact('branch', 'otherBranches'));
    }

    // API endpoint untuk get location (AJAX)
    public function getNearbyBranches(Request $request)
    {
        $branches = Branch::where('is_active', true)->get();
        
        return response()->json([
            'success' => true,
            'branches' => $branches
        ]);
    }
}