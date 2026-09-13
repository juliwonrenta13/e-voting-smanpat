<?php

namespace App\Http\Middleware;

use App\Models\Voter;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VoterAuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $voterId = $request->session()->get('voter_id');

        if (!$voterId) {
            return redirect()->route('voter.login')->with('error', 'Silakan masukkan token pemilihan terlebih dahulu.');
        }

        $voter = Voter::find($voterId);

        if (!$voter) {
            $request->session()->forget(['voter_id', 'voter_token']);
            return redirect()->route('voter.login')->with('error', 'Data pemilih tidak valid.');
        }

        if ($voter->status === 'used') {
            $request->session()->forget(['voter_id', 'voter_token']);
            return redirect()->route('voter.login')->with('error', 'Token ini sudah digunakan untuk memberikan suara.');
        }

        if ($voter->status === 'disabled') {
            $request->session()->forget(['voter_id', 'voter_token']);
            return redirect()->route('voter.login')->with('error', 'Token ini telah dinonaktifkan oleh panitia.');
        }

        // Attach voter model to request for convenience in controllers
        $request->attributes->set('voter', $voter);

        return $next($request);
    }
}
