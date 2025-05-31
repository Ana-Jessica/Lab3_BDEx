<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDeveloper
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está autenticado como desenvolvedor
        if (!auth('developer')->check()) {
            // Redireciona para a página de login com mensagem de erro
            return redirect()->route('login')->with('error', 'Acesso restrito a desenvolvedores cadastrados.');
        }

        // Verifica se a conta do desenvolvedor está ativa
        if (auth('developer')->user()->deleted_at !== null) {
            auth('developer')->logout();
            return redirect()->route('login')->with('error', 'Sua conta foi desativada.');
        }

        return $next($request);
    }
}