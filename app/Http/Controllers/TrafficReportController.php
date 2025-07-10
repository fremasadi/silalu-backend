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
        $query = TrafficReport::with(['traffic.kecamatan', 'confirmedUser', 'createdBy'])->latest();
    
        // Filter berdasarkan role
        if ($user->role === 'user') {
            // User hanya lihat laporan yang ia buat
            $query->where('created_by', $user->id);
        } elseif ($user->role === 'petugas') {
            // Petugas hanya lihat laporan dari traffic yang berada di kecamatan yang sama
            $query->whereHas('traffic', function ($q) use ($user) {
                $q->where('kecamatan_id', $user->kecamatan_id);
            });
        }
    
        $reports = $query->get();
    
        // Format response
        $reports = $reports->map(function ($report) {
            return [
                'id' => $report->id,
                'traffic_id' => $report->traffic_id,
                'masalah' => $report->masalah,
                'deskripsi' => $report->deskripsi, // ✅ Tambah ini
                'foto' => $report->foto,
                'status' => $report->status,
                'created_at' => $report->created_at,
                'updated_at' => $report->updated_at,
                'confirmed_by' => $report->confirmedUser?->name,
                'bukti_konfirmasi' => $report->bukti_konfirmasi,
                'traffic' => $report->traffic,
                'created_by' => $report->createdBy?->name,
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
            'deskripsi'  => 'nullable|string', // ✅ Tambah validasi
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
            'deskripsi'  => $request->deskripsi, // ✅ Tambah ini
            'foto'       => $fotoPath,
            'status'     => $request->status ?? 'pending',
            'created_by' => auth()->id(),
        ]);
    
        return response()->json([
            'message' => 'Laporan berhasil dikirim.',
            'data'    => $report
        ], 201);
    }
    

}
