<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:developer');
    }

    public function create(Connection $connection)
    {
        $this->authorize('evaluate', $connection);
        return view('developer.evaluations.create', compact('connection'));
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
            'tipo_avaliador' => 'developer',
            'id_avaliador' => auth('developer')->id(),
            'nota' => $validated['nota'],
            'comentario' => $validated['comentario'],
        ]);

        return redirect()->route('developer.connections.show', $connection)
            ->with('success', 'Avaliação registrada com sucesso!');
    }
}