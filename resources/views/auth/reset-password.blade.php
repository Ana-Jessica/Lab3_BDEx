@extends('layouts.guest')

@section('title', 'Redefinir Senha')

@section('auth-content')
<div class="text-center mb-4">
    <h2>Redefinir Senha</h2>
    <p class="text-muted">Crie uma nova senha</p>
</div>

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ $email ?? old('email') }}" required autofocus>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Nova Senha</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirme a Nova Senha</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Redefinir Senha</button>
</form>
@endsection