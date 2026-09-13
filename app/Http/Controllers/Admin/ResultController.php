<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElectionSetting;
use App\Models\Position;
use App\Models\Voter;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $section = $request->get('section');
        $setting = ElectionSetting::current();

        $query = Position::ordered()->with(['candidates' => function ($q) {
            $q->withCount('votes')->orderBy('votes_count', 'desc');
        }]);

        if ($section && in_array($section, ['OSIS', 'MPK'])) {
            $query->where('section', $section);
        }

        $positions = $query->get();

        $totalVoters = Voter::count();
        $totalVoted = Voter::used()->count();
        $turnout = $totalVoters > 0 ? round(($totalVoted / $totalVoters) * 100, 1) : 0;

        return view('admin.results.index', compact('positions', 'setting', 'totalVoters', 'totalVoted', 'turnout', 'section'));
    }

    public function print(Request $request)
    {
        $setting = ElectionSetting::current();
        $positions = Position::ordered()->with(['candidates' => function ($q) {
            $q->withCount('votes')->orderBy('votes_count', 'desc');
        }])->get();

        $totalVoters = Voter::count();
        $totalVoted = Voter::used()->count();
        $turnout = $totalVoters > 0 ? round(($totalVoted / $totalVoters) * 100, 1) : 0;

        return view('admin.results.print', compact('positions', 'setting', 'totalVoters', 'totalVoted', 'turnout'));
    }
}
