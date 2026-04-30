<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Daftar laporan untuk Owner
     */
    public function index()
    {
        $reports = Report::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('owner.reports', compact('reports'));
    }

    /**
     * Detail laporan
     */
    public function show($id)
    {
        $report = Report::with('creator')->findOrFail($id);
        
        // Tandai sudah dibaca
        if (!$report->is_read) {
            $report->is_read = true;
            $report->read_at = now();
            $report->save();
        }
        
        return view('owner.report-detail', compact('report'));
    }

    /**
     * Approve laporan oleh Owner
     */
    public function approve($id)
    {
        $report = Report::findOrFail($id);
        $report->status = 'approved';
        $report->save();
        
        return redirect()->back()->with('success', 'Laporan telah disetujui');
    }
}