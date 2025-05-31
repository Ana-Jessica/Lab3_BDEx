<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::guard()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $guard = Auth::guard()->getName();
        $user = Auth::guard()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:companies,email,'.$user->id_empresa.',id_empresa|unique:developers,email_desenvolvedor,'.$user->id_desenvolvedor.',id_desenvolvedor',
            'phone' => 'required|string',
            'address' => 'required|string',
        ];

        if ($guard === 'company') {
            $rules['cnpj'] = 'required|string|unique:companies,cnpj,'.$user->id_empresa.',id_empresa';
        } else {
            $rules['cpf'] = 'required|string|unique:developers,cpf,'.$user->id_desenvolvedor.',id_desenvolvedor';
        }

        $validated = $request->validate($rules);

        if ($guard === 'company') {
            $user->update([
                'nome_empresa' => $validated['name'],
                'cnpj' => $validated['cnpj'],
                'email' => $validated['email'],
                'telefone_empresa' => $validated['phone'],
                'endereco' => $validated['address'],
            ]);
        } else {
            $user->update([
                'nome_desenvolvedor' => $validated['name'],
                'cpf' => $validated['cpf'],
                'email_desenvolvedor' => $validated['email'],
                'telefone_desenvolvedor' => $validated['phone'],
                'endereco' => $validated['address'],
            ]);
        }

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
}