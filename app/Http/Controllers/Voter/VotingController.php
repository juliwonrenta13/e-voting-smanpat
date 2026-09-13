<?php

namespace App\Http\Controllers\Voter;

use App\Http\Controllers\Controller;
use App\Models\ElectionSetting;
use App\Models\Position;
use App\Models\Voter;
use App\Services\VotingService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VotingController extends Controller
{
    protected VotingService $votingService;

    public function __construct(VotingService $votingService)
    {
        $this->votingService = $votingService;
    }

    public function index(Request $request)
    {
        $voter = $request->attributes->get('voter') ?? Voter::find(session('voter_id'));

        $setting = ElectionSetting::current();
        $positions = Position::active()
            ->ordered()
            ->with(['activeCandidates'])
            ->get();

        $groupedPositions = [
            'OSIS' => $positions->where('section', 'OSIS'),
            'MPK' => $positions->where('section', 'MPK'),
        ];

        return view('voter.ballot', compact('voter', 'setting', 'positions', 'groupedPositions'));
    }

    public function submit(Request $request)
    {
        $voterId = session('voter_id');
        if (!$voterId) {
            return redirect()->route('voter.login')->with('error', 'Sesi pemilihan Anda telah berakhir.');
        }

        $selections = $request->input('selections', []);

        if (!is_array($selections) || empty($selections)) {
            return back()->with('error', 'Mohon lengkapi seluruh 6 pilihan jabatan sebelum mengirim surat suara.');
        }

        try {
            $receipt = $this->votingService->submitBallot($voterId, $selections);

            // Invalidate voter session completely so voter cannot vote again
            $request->session()->forget(['voter_id', 'voter_token']);

            // Store receipt data for one-time display on success page
            $request->session()->flash('receipt', $receipt);

            return redirect()->route('voting.success');
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return back()->withInput()->with('error', $firstError ?? 'Validasi surat suara gagal.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kendala saat memproses suara Anda: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $receipt = $request->session()->get('receipt');

        // If accessed directly without just having voted, show standard thank you
        return view('voter.success', compact('receipt'));
    }
}
