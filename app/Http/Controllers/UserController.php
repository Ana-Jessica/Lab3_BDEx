<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showDeactivateForm()
    {
        return view('profile.deactivate');
    }

    public function deactivate(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::guard()->user();
        
        if (Auth::guard()->getName() === 'company') {
            $user->delete();
            Auth::guard('company')->logout();
        } else {
            $user->delete();
            Auth::guard('developer')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Sua conta foi desativada com sucesso.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::guard()->user();
        $passwordField = Auth::guard()->getName() === 'company' ? 'senha_empresa' : 'senha_desenvolvedor';

        $user->update([
            $passwordField => Hash::make($request->password),
        ]);

        return back()->with('success', 'Senha alterada com sucesso!');
    }
}