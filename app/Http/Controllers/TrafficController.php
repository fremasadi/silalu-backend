<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Traffic;

class TrafficController extends Controller
{
    public function index()
    {
        $data = Traffic::all();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user(); // ✅ Auth user via token

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'jenis_apill' => 'required|string|max:255',
            'kelurahan_id' => 'nullable|exists:kelurahans,id',
        ]);

        // ✅ Jika user petugas, set kecamatan otomatis
        if ($user && $user->role === 'petugas') {
            $validated['kecamatan_id'] = $user->kecamatan_id;
        } else {
            // Jika bukan petugas, wajibkan input manual
            $validated['kecamatan_id'] = $request->input('kecamatan_id');
        }

        $traffic = Traffic::create($validated);

        return response()->json([
            'message' => 'Data APILL berhasil ditambahkan.',
            'data' => $traffic,
        ]);
    }
}
