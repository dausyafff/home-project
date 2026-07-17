<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Password::sendResetLink() melakukan 3 hal sekaligus:
        // 1. Cek apakah email ada di database
        // 2. Generate token acak → simpan ke tabel password_reset_tokens
        // 3. Kirim email berisi link reset (pakai konfigurasi Mail di .env)
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // $status bisa bernilai:
        // Password::RESET_LINK_SENT → email berhasil dikirim
        // Password::INVALID_USER   → email tidak ditemukan di database
        if ($status === Password::RESET_LINK_SENT) {
            return $this->success(null, 'Link reset password telah dikirim ke email kamu');
        }

        return $this->error('Email tidak ditemukan', 404);
    }
}