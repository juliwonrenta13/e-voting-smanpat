<?php

namespace App\Http\Middleware;

use App\Models\ElectionSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ElectionActiveMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $setting = ElectionSetting::current();

        if (!$setting->isVotingOpen()) {
            $msg = 'Bilik pemilihan belum dibuka atau telah ditutup.';
            if ($setting->status === 'draft') {
                $msg = 'Pemilihan masih dalam status draft.';
            } elseif ($setting->status === 'scheduled') {
                $msg = 'Pemilihan dijadwalkan pada ' . ($setting->start_at ? $setting->start_at->translatedFormat('d F Y H:i') : '-') . ' sampai ' . ($setting->end_at ? $setting->end_at->translatedFormat('d F Y H:i') : '-');
            } elseif ($setting->status === 'closed') {
                $msg = 'Periode pemilihan telah resmi ditutup.';
            }

            return redirect()->route('voter.login')->with('error', $msg);
        }

        return $next($request);
    }
}
