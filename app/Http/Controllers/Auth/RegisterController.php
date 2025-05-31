<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
    {        
        if (Auth::check()) {
            return redirect('/'); // Redireciona para a página inicial se já estiver autenticado
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_type' => 'required|in:company,developer',
            'name' => 'required|string|max:255',
            'document' => 'required|string',
            'email' => 'required|string|email|max:255|unique:companies,email|unique:developers,email_desenvolvedor',
            'phone' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required_if:user_type,company',
            'company_description' => 'nullable|string',
            'skills' => 'required_if:user_type,developer',
            'experience' => 'required_if:user_type,developer'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } 

        if ($data['user_type'] === 'company') {
            $company = Company::create([
                'nome_empresa' => $data['name'],
                'cnpj' => $data['document'],
                'email' => $data['email'],
                'telefone_empresa' => $data['phone'],
                'endereco' => $data['address'],
                'descricao' => $data['company_description'] ?? null,
                'senha_empresa' => Hash::make($data['password']),
            ]);

            Auth::guard('company')->login($company);
            return redirect()->route('company.dashboard');
        } else {
            $developer = Developer::create([
                'nome_desenvolvedor' => $data['name'],
                'cpf' => $data['document'],
                'email_desenvolvedor' => $data['email'],
                'telefone_desenvolvedor' => $data['phone'],
                'habilidades' => $data['skills'],
                'experiencia' => $data['experience'],
                'senha_desenvolvedor' => Hash::make($data['password']),
            ]);

            Auth::guard('developer')->login($developer);
            return redirect()->route('developer.dashboard');
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'document' => 'required|string',
            'email' => 'required|string|email|max:255|unique:companies,email,' . $id . ',id|unique:developers,email_desenvolvedor,' . $id . ',id',
            'phone' => 'required|string',
            'address' => 'required_if:user_type,company',
            'company_description' => 'nullable|string',
            'skills' => 'required_if:user_type,developer',
            'experience' => 'required_if:user_type,developer'
        ]);
    }
}