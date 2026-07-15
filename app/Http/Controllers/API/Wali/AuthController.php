<?php

namespace App\Http\Controllers\Api\Wali;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($validated)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        $user = $request->user();

        if (! $user->hasRole('Wali Santri')) {
            $user->tokens()->delete();

            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan akun Wali Santri.',
            ], 403);
        }

        $token = $user->createToken('wali-mobile')->plainTextToken;

        $santris = Santri::query()
            ->with(['kelas', 'asrama'])
            ->where('user_id', $user->id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'santris' => $santris,
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        $santris = Santri::query()
            ->with(['kelas', 'asrama'])
            ->where('user_id', $user->id)
            ->get();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'santris' => $santris,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }
}
