@extends('layouts.guest')
@section('content')
@section('title', 'Login')

@section('auth-content')
<div class="text-center mb-4">
    <h2>Login</h2>
    <p class="text-muted">Acesse sua conta</p>
</div>

@include('components.alert')

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" required autofocus>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Senha</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">Manter conectado</label>
    </div>
    <button type="submit" class="btn btn-primary w-100">Entrar</button>
</form>

<div class="mt-3 text-center">
    <a href="{{ route('password.request') }}">Esqueceu sua senha?</a>
</div>
<div class="mt-2 text-center">
    Não tem conta? <a href="{{ route('register') }}">Cadastre-se</a>
</div>
@endsection