<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
   

    /**
     * Get user profile from auth token.
     */
    public function profile(Request $request)
    {
        $user = Auth::user(); // atau $request->user();

        return response()->json([
            'status' => true,
            'message' => 'Authenticated user profile fetched successfully',
            'user' => $user,
        ]);
    }
}
