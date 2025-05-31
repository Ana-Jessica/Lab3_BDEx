<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:company');
    }

    public function create(Connection $connection)
    {
        $this->authorize('evaluate', $connection);
        return view('company.evaluations.create', compact('connection'));
    }

    public function store(Request $request, Connection $connection)
    {
        $this->authorize('evaluate', $connection);

        $validated = $request->validate([
            'nota' => 'required|integer|between:1,5',
            'comentario' => 'nullable|string|max:500',
        ]);

        Evaluation::create([
            'id_conexao' => $connection->id_conexao,
            'tipo_avaliador' => 'company',
            'id_avaliador' => auth('company')->id(),
            'nota' => $validated['nota'],
            'comentario' => $validated['comentario'],
        ]);

        return redirect()->route('company.connections.show', $connection)
            ->with('success', 'Avaliação registrada com sucesso!');
    }
}