<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está autenticado como empresa
        if (!auth('company')->check()) {
            // Redireciona para a página de login com mensagem de erro
            return redirect()->route('login')->with('error', 'Acesso restrito a empresas cadastradas.');
        }

        // Verifica se a conta da empresa está ativa
        if (auth('company')->user()->deleted_at !== null) {
            auth('company')->logout();
            return redirect()->route('login')->with('error', 'Sua conta foi desativada.');
        }

        return $next($request);
    }
}