<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrafficReport;
use Illuminate\Support\Facades\Storage;

class TrafficReportController extends Controller
{

    public function index()
{
    $reports = TrafficReport::with('traffic')->latest()->get();

    return response()->json([
        'data' => $reports
    ]);
}
    public function store(Request $request)
    {
        $request->validate([
            'traffic_id' => 'required|exists:traffic,id',
            'masalah'    => 'required|string',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'     => 'nullable|in:pending,proses,selesai'
        ]);

        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('traffic_reports', 'public');
        }

        $report = TrafficReport::create([
            'traffic_id' => $request->traffic_id,
            'masalah'    => $request->masalah,
            'foto'       => $fotoPath,
            'status'     => $request->status ?? 'pending',
        ]);

        return response()->json([
            'message' => 'Laporan berhasil dikirim.',
            'data'    => $report
        ], 201);
    }
}
