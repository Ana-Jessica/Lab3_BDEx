<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use App\Models\Request as Solicitation; // Alias para evitar conflito
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OpportunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:developer');
    }

    public function index(): View
    {
        $opportunities = Opportunity::with('company')->get();
        return view('developer.opportunities.index', compact('opportunities'));
    }

    public function show(Opportunity $opportunity): View
    {
        $hasApplied = Solicitation::where('id_desenvolvedor', auth('developer')->id())
            ->where('id_vaga', $opportunity->id_vaga)
            ->exists();

        return view('developer.opportunities.show', compact('opportunity', 'hasApplied'));
    }

    public function apply(Request $request, Opportunity $opportunity): RedirectResponse
    {
        Solicitation::create([
            'id_desenvolvedor' => auth('developer')->id(),
            'id_vaga' => $opportunity->id_vaga,
        ]);

        return back()->with('success', 'Solicitação enviada com sucesso!');
    }
}