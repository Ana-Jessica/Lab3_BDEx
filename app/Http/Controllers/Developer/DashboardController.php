<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Connection;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:developer');
    }

    public function index()
    {
        $developer = auth('developer')->user();
        $connections = Connection::where('id_desenvolvedor', $developer->id_desenvolvedor)->count();
        $requests = Request::where('id_desenvolvedor', $developer->id_desenvolvedor)->count();

        return view('developer.dashboard', compact('developer', 'connections', 'requests'));
    }
}