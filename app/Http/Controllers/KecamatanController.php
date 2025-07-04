<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    /**
     * Return all kecamatan as JSON.
     */
    public function index()
    {
        $kecamatan = Kecamatan::all();

        return response()->json([
            'success' => true,
            'data' => $kecamatan,
        ]);
    }
}
