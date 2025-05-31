<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\Developer;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tentar autenticar como empresa
        if (Auth::guard('company')->attempt(['email' => $credentials['email'], 'senha_empresa' => $credentials['password']], $request->remember)) {
            return redirect()->intended('/company/dashboard');
        }

        // Tentar autenticar como desenvolvedor
        if (Auth::guard('developer')->attempt(['email_desenvolvedor' => $credentials['email'], 'senha_desenvolvedor' => $credentials['password']], $request->remember)) {
            return redirect()->intended('/developer/dashboard');
        }

        return back()->withErrors([
            'email' => 'Credenciais inválidas.',
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('company')->check()) {
            Auth::guard('company')->logout();
        } elseif (Auth::guard('developer')->check()) {
            Auth::guard('developer')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}