<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
public function index()
    {
        return view('admin.scanner.scanner');
    }

    /**
     * Handle scanned data.
     */
    public function process(Request $request)
    {
        $data = $request->input('data'); // Get the scanned data

        // Example: Find a user by ID
        $user = User::find($data);

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'User found!',
                'user' => $user,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No user found for this QR code.',
        ]);
    }
}
