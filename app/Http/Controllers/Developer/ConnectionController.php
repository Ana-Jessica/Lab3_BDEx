<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:developer');
    }

    public function index()
    {
        $connections = Connection::with('company', 'opportunity')
            ->where('id_desenvolvedor', auth('developer')->id())
            ->get();

        return view('developer.connections.index', compact('connections'));
    }

    public function show(Connection $connection)
    {
        $this->authorize('view', $connection);
        return view('developer.connections.show', compact('connection'));
    }
}