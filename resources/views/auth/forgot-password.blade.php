@extends('layouts.guest')

@section('title', 'Recuperar Senha')

@section('auth-content')
<div class="text-center mb-4">
    <h2>Recuperar Senha</h2>
    <p class="text-muted">Informe seu e-mail para receber o link de recuperação</p>
</div>

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" required autofocus>
    </div>
    <button type="submit" class="btn btn-primary w-100">Enviar Link</button>
</form>

<div class="mt-3 text-center">
    <a href="{{ route('login') }}">Voltar ao login</a>
</div>
@endsection