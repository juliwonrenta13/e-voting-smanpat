<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['Akun Anda tidak memiliki hak akses administrator.'],
                ]);
            }

            $request->session()->regenerate();
            AuditLogService::log('ADMIN_LOGIN', 'Administrator berhasil login ke sistem.');

            return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali, ' . Auth::user()->name);
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau kata sandi yang Anda masukkan salah.'],
        ]);
    }

    public function logout(Request $request)
    {
        AuditLogService::log('ADMIN_LOGOUT', 'Administrator logout dari sistem.');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil keluar.');
    }
}
