<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Opportunity;
use App\Models\Connection;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:company');
    }

    public function index()
    {
        $company = auth('company')->user();
        $opportunities = Opportunity::where('id_empresa', $company->id_empresa)->count();
        $connections = Connection::where('id_empresa', $company->id_empresa)->count();

        return view('company.dashboard', compact('company', 'opportunities', 'connections'));
    }
}