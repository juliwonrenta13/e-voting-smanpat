<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use App\Services\AuditLogService;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TokenController extends Controller
{
    protected TokenService $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');

        $query = Voter::latest();

        if ($status && in_array($status, ['unused', 'used', 'disabled'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where('token', 'like', "%{$search}%");
        }

        $voters = $query->paginate(25)->withQueryString();

        $counts = [
            'total' => Voter::count(),
            'unused' => Voter::unused()->count(),
            'used' => Voter::used()->count(),
            'disabled' => Voter::disabled()->count(),
        ];

        return view('admin.tokens.index', compact('voters', 'counts', 'status', 'search'));
    }

    public function generateSingle()
    {
        $token = $this->tokenService->generateToken(8);
        $voter = Voter::create([
            'token' => $token,
            'status' => 'unused',
        ]);

        AuditLogService::log('TOKEN_GENERATE_SINGLE', "Membuat 1 token baru: {$token}");

        return back()->with('success', "Token {$token} berhasil digenerate.");
    }

    public function generateBatch(Request $request)
    {
        $request->validate([
            'count' => ['required', 'integer', 'min:1', 'max:1000'],
            'length' => ['nullable', 'integer', 'min:6', 'max:16'],
        ]);

        $count = (int) $request->input('count');
        $length = (int) ($request->input('length') ?? 8);

        $created = $this->tokenService->generateBatch($count, $length);
        AuditLogService::log('TOKEN_GENERATE_BATCH', "Membuat {$created} token baru secara massal.");

        return back()->with('success', "Berhasil membuat {$created} token baru.");
    }

    public function toggleStatus(Voter $voter)
    {
        if ($voter->status === 'used') {
            return back()->with('error', 'Token yang sudah digunakan tidak dapat dinonaktifkan.');
        }

        $newStatus = $voter->status === 'unused' ? 'disabled' : 'unused';
        $voter->update(['status' => $newStatus]);

        AuditLogService::log('TOKEN_TOGGLE', "Mengubah status token {$voter->token} menjadi {$newStatus}");

        return back()->with('success', "Status token {$voter->token} diubah menjadi {$newStatus}.");
    }

    public function resetToken(Voter $voter)
    {
        // Reset only unused/disabled, or if used, clear votes with caution and audit log
        $prevStatus = $voter->status;

        // If used, delete associated votes
        if ($voter->status === 'used') {
            $voter->votes()->delete();
        }

        $voter->update([
            'status' => 'unused',
            'voted_at' => null,
        ]);

        AuditLogService::log('TOKEN_RESET', "Mereset status token {$voter->token} dari status {$prevStatus} menjadi unused");

        return back()->with('success', "Token {$voter->token} berhasil di-reset menjadi status unused.");
    }

    public function destroy(Voter $voter)
    {
        if ($voter->status === 'used') {
            return back()->with('error', 'Token yang sudah digunakan tidak dapat dihapus.');
        }

        $tokenStr = $voter->token;
        $voter->delete();

        AuditLogService::log('TOKEN_DELETE', "Menghapus token: {$tokenStr}");

        return back()->with('success', "Token {$tokenStr} berhasil dihapus.");
    }

    public function export(Request $request): StreamedResponse
    {
        $status = $request->get('status');
        $fileName = 'daftar-token-evoting-' . date('Y-m-d-His') . '.csv';

        $query = Voter::orderBy('id', 'asc');
        if ($status && in_array($status, ['unused', 'used', 'disabled'])) {
            $query->where('status', $status);
        }

        $voters = $query->get();
        AuditLogService::log('TOKEN_EXPORT', "Mengekspor " . $voters->count() . " data token ke CSV.");

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($voters) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'ID', 'Token', 'Status', 'Waktu Memberikan Suara', 'Dibuat Pada']);

            $no = 1;
            foreach ($voters as $v) {
                fputcsv($handle, [
                    $no++,
                    $v->id,
                    $v->token,
                    $v->status,
                    $v->voted_at ? $v->voted_at->format('Y-m-d H:i:s') : '-',
                    $v->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}
