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
    ]);

    $trafficReport = TrafficReport::findOrFail($id);

    // Simpan gambar ke storage/app/public/konfirmasi/
    $path = $request->file('bukti_konfirmasi')->store('konfirmasi', 'public');

    $trafficReport->update([
        'confirmed_by' => $request->user()->id,
        'bukti_konfirmasi' => $path,
        'status' => 'proses', // ✅ Ubah status menjadi 'completed'
    ]);

    return response()->json([
        'message' => 'Laporan berhasil dikonfirmasi dan ditandai sebagai selesai.',
        'data' => $trafficReport,
    ]);
}

}
