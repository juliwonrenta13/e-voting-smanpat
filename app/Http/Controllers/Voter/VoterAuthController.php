<?php

namespace App\Http\Controllers\Voter;

use App\Http\Controllers\Controller;
use App\Models\ElectionSetting;
use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VoterAuthController extends Controller
{
    public function showLoginForm()
    {
        // If already logged in and token is unused, redirect to voting
        if (session()->has('voter_id')) {
            $voter = Voter::find(session('voter_id'));
            if ($voter && $voter->status === 'unused') {
                return redirect()->route('voting.index');
            }
        }

        $setting = ElectionSetting::current();
        return view('voter.auth.login', compact('setting'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string', 'min:4', 'max:20'],
        ], [
            'token.required' => 'Silakan masukkan token pemilihan Anda.',
            'token.min' => 'Format token minimal 4 karakter.',
        ]);

        $setting = ElectionSetting::current();
        if (!$setting->isVotingOpen()) {
            $message = 'Bilik pemilihan sedang tidak dibuka.';
            if ($setting->status === 'draft') {
                $message = 'Pemilihan belum dimulai (status masih draft).';
            } elseif ($setting->status === 'scheduled') {
                $start = $setting->start_at ? $setting->start_at->translatedFormat('d M Y H:i') : '-';
                $message = "Pemilihan belum dibuka. Jadwal dimulai pada: {$start}";
            } elseif ($setting->status === 'closed') {
                $message = 'Pemilihan telah resmi ditutup.';
            }

            throw ValidationException::withMessages([
                'token' => [$message],
            ]);
        }

        $inputToken = strtoupper(trim($request->input('token')));
        $voter = Voter::where('token', $inputToken)->first();

        if (!$voter) {
            throw ValidationException::withMessages([
                'token' => ['Token tidak ditemukan. Pastikan Anda memasukkan kode token yang benar dari panitia.'],
            ]);
        }

        if ($voter->status === 'used') {
            $votedTime = $voter->voted_at ? $voter->voted_at->translatedFormat('d F Y H:i') : 'sebelumnya';
            throw ValidationException::withMessages([
                'token' => ["Token ini telah digunakan untuk memberikan suara pada {$votedTime}. Satu token hanya berlaku satu kali."],
            ]);
        }

        if ($voter->status === 'disabled') {
            throw ValidationException::withMessages([
                'token' => ['Token ini telah dinonaktifkan oleh panitia. Silakan hubungi panitia pemilihan.'],
            ]);
        }

        // Regenerate session for security
        $request->session()->regenerate();
        $request->session()->put('voter_id', $voter->id);
        $request->session()->put('voter_token', $voter->token);

        return redirect()->route('voting.index')->with('success', 'Selamat datang di bilik suara digital. Silakan tentukan pilihan Anda.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['voter_id', 'voter_token']);
        return redirect()->route('voter.login')->with('info', 'Anda telah keluar dari sesi pemilihan.');
    }
}
