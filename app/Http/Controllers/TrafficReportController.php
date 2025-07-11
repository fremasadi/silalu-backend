<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrafficReport;
use Illuminate\Support\Facades\Storage;

class TrafficReportController extends Controller
{

    public function index()
{
    $user = auth()->user();

    // Ambil query awal
    $query = TrafficReport::with(['traffic', 'confirmedUser', 'createdBy'])->latest();

    // Filter berdasarkan role
    if ($user->role === 'user') {
        $query->where('created_by', $user->id);
    }

    $reports = $query->get();

    // Format response
    $reports = $reports->map(function ($report) {
        return [
            'id' => $report->id,
            'traffic_id' => $report->traffic_id,
            'masalah' => $report->masalah,
            'deskripsi' => $report->deskripsi,
            'foto' => $report->foto,
            'status' => $report->status,
            'created_at' => $report->created_at,
            'updated_at' => $report->updated_at,
            'confirmed_by' => $report->confirmedUser?->name,
            'bukti_konfirmasi' => $report->bukti_konfirmasi,
            'traffic' => $report->traffic,
            'created_by' => $report->createdBy?->name, // tampilkan nama pembuat
        ];
    });

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
        'created_by' => auth()->id(), // Tambahkan ini
    ]);

    return response()->json([
        'message' => 'Laporan berhasil dikirim.',
        'data'    => $report
    ], 201);
}

public function apillDamageStats()
    {
        $stats = [
            '3_lampu' => $this->countDamagedApill('3 lampu'),
            '2_lampu' => $this->countDamagedApill('2 lampu'),
            '1_lampu' => $this->countDamagedApill('1 lampu'),
        ];

        return response()->json([
            'message' => 'Statistik kerusakan APILL',
            'data' => $stats,
        ]);
    }

    protected function countDamagedApill(string $jenis): int
    {
        return TrafficReport::whereIn('status', ['pending', 'proses'])
            ->whereHas('traffic', function ($query) use ($jenis) {
                $query->where('jenis_apill', $jenis);
            })
            ->count();
    }

}
