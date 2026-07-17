<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        // Password::reset() melakukan:
        // 1. Verifikasi token cocok dengan email
        // 2. Cek token belum expired (default 60 menit)
        // 3. Jalankan callback (update password)
        // 4. Hapus token dari tabel password_reset_tokens
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                // Hapus semua token Sanctum yang lama
                // Supaya tidak ada yang masih bisa login dengan password lama
                $user->tokens()->delete();

                // Trigger event — bisa dipakai untuk logging, dll
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->success(null, 'Password berhasil direset. Silakan login dengan password baru.');
        }

        // $status bisa berisi pesan error spesifik:
        // Password::INVALID_TOKEN → token salah atau expired
        // Password::INVALID_USER  → email tidak ditemukan
        return $this->error(__($status), 400);
    }
}
