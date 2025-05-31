<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OpportunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:company');
    }

    public function index(): View
    {
        $opportunities = Opportunity::where('id_empresa', auth('company')->id())->get();
        return view('company.opportunities.index', compact('opportunities'));
    }

    public function create(): View
    {
        return view('company.opportunities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titulo_vaga' => 'required|string|max:255',
            'descricao_vaga' => 'required|string',
            'valor_oferta' => 'nullable|numeric',
        ]);

        Opportunity::create([
            'id_empresa' => auth('company')->id(),
            'titulo_vaga' => $validated['titulo_vaga'],
            'descricao_vaga' => $validated['descricao_vaga'],
            'valor_oferta' => $validated['valor_oferta'],
        ]);

        return redirect()->route('company.opportunities.index')
            ->with('success', 'Vaga criada com sucesso!');
    }
}