<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\ElectionSetting;
use App\Models\Position;
use App\Models\Vote;
use App\Models\Voter;

class DashboardController extends Controller
{
    public function index()
    {
        $setting = ElectionSetting::current();

        $totalVoters = Voter::count();
        $votedCount = Voter::used()->count();
        $unvotedCount = Voter::unused()->count();
        $disabledCount = Voter::disabled()->count();

        $turnoutPercentage = $totalVoters > 0 ? round(($votedCount / $totalVoters) * 100, 1) : 0;

        $positionsCount = Position::count();
        $candidatesCount = Candidate::count();
        $totalVotesCast = Vote::count();

        $positions = Position::ordered()->withCount(['candidates', 'votes'])->get();
        $recentAuditLogs = AuditLog::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'setting',
            'totalVoters',
            'votedCount',
            'unvotedCount',
            'disabledCount',
            'turnoutPercentage',
            'positionsCount',
            'candidatesCount',
            'totalVotesCast',
            'positions',
            'recentAuditLogs'
        ));
    }
}
