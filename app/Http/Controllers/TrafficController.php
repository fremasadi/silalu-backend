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
}
