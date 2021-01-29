<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show profile page.
     *
     * @return
     */
    public function index()
    {
        return view('pages.administrator.profile', [
            'title' => 'Profile',
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update user profile.
     *
     * @param   Request  $request
     *
     * @return  JsonResponse
     */
    public function update(Request $request)
    {
        $payload = $request->validate([
            'password' => 'required|string|max:255|confirmed'
        ]);

        // Hash password.
        $payload['password'] = Hash::make($payload['password']);

        // Get and update user.
        $auth = $request->user();
        $auth->update($payload);

        return response()->json([
            'ok' => true,
            'message' => 'Profile has been updated.',
            'data' => $auth
        ]);
    }
}
