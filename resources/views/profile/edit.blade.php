@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Editar Perfil</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ Auth::user()->nome_empresa ?? Auth::user()->nome_desenvolvedor }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ Auth::user()->email ?? Auth::user()->email_desenvolvedor }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefone</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="{{ Auth::user()->telefone_empresa ?? Auth::user()->telefone_desenvolvedor }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Endereço</label>
                        <input type="text" class="form-control" id="address" name="address" 
                               value="{{ Auth::user()->endereco }}" required>
                    </div>
                    
                    @if(Auth::guard('developer')->check())
                    <div class="mb-3">
                        <label for="technologies" class="form-label">Tecnologias</label>
                        <input type="text" class="form-control" id="technologies" name="technologies" 
                               value="{{ Auth::user()->tecnologias }}">
                        <small class="text-muted">Separe por vírgula</small>
                    </div>
                    @endif
                    
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h4>Alterar Senha</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Senha Atual</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirme a Nova Senha</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Alterar Senha</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection