<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use App\Models\Request as Solicitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConnectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:company');
    }

    public function index(): View
    {
        $connections = Connection::with('developer')
            ->where('id_empresa', auth('company')->id())
            ->get();

        return view('company.connections.index', compact('connections'));
    }

    public function show(Connection $connection): View
    {
        $this->authorize('view', $connection);
        return view('company.connections.show', compact('connection'));
    }

    public function acceptRequest(Request $request, Solicitation $solicitation): RedirectResponse
    {
        $this->authorize('accept', $solicitation);

        $connection = Connection::create([
            'id_empresa' => auth('company')->id(),
            'id_desenvolvedor' => $solicitation->id_desenvolvedor,
            'id_vaga' => $solicitation->id_vaga,
            'data_conexao' => now(),
        ]);

        $solicitation->delete();

        return redirect()->route('company.connections.show', $connection)
            ->with('success', 'Solicitação aceita com sucesso!');
    }
}