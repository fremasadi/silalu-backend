<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TrafficReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrafficConfirmationController extends Controller
{
    public function confirm(Request $request, $id)
{
    $request->validate([
        'bukti_konfirmasi' => 'required|image|max:2048',
        'deskripsi' => 'nullable|string', // ✅ Tambahkan ini

    ]);

    $trafficReport = TrafficReport::findOrFail($id);

    // Simpan gambar ke storage/app/public/konfirmasi/
    $path = $request->file('bukti_konfirmasi')->store('konfirmasi', 'public');

    $trafficReport->update([
        'confirmed_by' => $request->user()->id,
        'bukti_konfirmasi' => $path,
        'deskripsi' => $request->deskripsi, // ✅ Tambahkan ini
        'status' => 'proses', // ✅ Ubah status menjadi 'completed'
    ]);

    return response()->json([
        'message' => 'Laporan berhasil dikonfirmasi dan ditandai sebagai selesai.',
        'data' => $trafficReport,
    ]);
}
public function markAsCompleted($id)
{
    $trafficReport = TrafficReport::findOrFail($id);

    // Pastikan hanya petugas yang bisa menyelesaikan laporan
    if (auth()->user()->role !== 'petugas') {
        return response()->json([
            'message' => 'Anda tidak memiliki izin untuk menyelesaikan laporan ini.'
        ], 403);
    }

    // Pastikan status sebelumnya adalah 'proses' agar tidak bisa skip konfirmasi
    if ($trafficReport->status !== 'proses') {
        return response()->json([
            'message' => 'Laporan hanya dapat diselesaikan setelah dikonfirmasi terlebih dahulu.'
        ], 400);
    }

    $trafficReport->update([
        'status' => 'selesai',
    ]);

    return response()->json([
        'message' => 'Laporan berhasil ditandai sebagai selesai.',
        'data' => $trafficReport,
    ]);
}


}
