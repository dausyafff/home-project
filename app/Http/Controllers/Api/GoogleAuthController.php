<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    use ApiResponse;

    // Step 1: Redirect user ke halaman login Google
    public function redirect()
    {
        // stateless() = tidak pakai session
        // Wajib untuk API yang tidak pakai cookie/session
        $url = Socialite::driver('google')
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        // Kirim URL Google ke frontend
        // Frontend yang akan redirect user ke URL ini
        return $this->success(['url' => $url], 'Google OAuth URL');
    }

    // Step 2: Google redirect balik ke sini dengan code
    public function callback()
    {
        try {
            // Tukar authorization code dengan data user Google
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            // Cek: apakah email sudah terdaftar?
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // User sudah ada — update google_id kalau belum ada
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            } else {
                // User belum ada — buat akun baru
                $user = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                    'password'  => null,
                    // password null karena login via Google
                    // tidak perlu password manual
                ]);
            }

            // Hapus token lama, buat token baru
            $user->tokens()->delete();
            $token = $user->createToken('google_auth_token')->plainTextToken;

            // Redirect ke frontend dengan token di URL
            // Frontend akan ambil token dari URL parameter
            $frontendUrl = config('app.frontend_url', 'http://localhost:5173');
            return redirect("{$frontendUrl}/auth/callback?token={$token}&name=" . urlencode($user->name) . "&email=" . urlencode($user->email));
        } catch (\Exception $e) {
            $frontendUrl = config('app.frontend_url', 'http://localhost:5173');
            return redirect("{$frontendUrl}/login?error=oauth_failed");
        }
    }
}
