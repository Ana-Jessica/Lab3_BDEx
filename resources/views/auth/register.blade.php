@extends('layouts.guest')

@section('title', 'Cadastro')

@section('auth-content')
<div class="text-center mb-4">
    <h2>Cadastre-se</h2>
    <p class="text-muted">Crie sua conta</p>
</div>

@include('components.alert')

<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="mb-3">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="user_type" id="type_company" value="company" checked>
            <label class="form-check-label" for="type_company">Empresa</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="user_type" id="type_developer" value="developer">
            <label class="form-check-label" for="type_developer">Desenvolvedor</label>
        </div>
    </div>

    <div class="mb-3">
        <label for="name" class="form-label" id="name_label">Razão Social</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="mb-3">
        <label for="document" class="form-label" id="document_label">CNPJ</label>
        <input type="text" class="form-control" id="document" name="document" required>
    </div>

    <!-- Campos específicos para empresa -->
    <div id="company_fields">
        <div class="mb-3">
            <label for="address" class="form-label">Endereço Completo</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        
        <div class="mb-3">
            <label for="company_description" class="form-label">Descrição da Empresa</label>
            <textarea class="form-control" id="company_description" name="company_description" rows="3"></textarea>
        </div>
    </div>

    <!-- Campos específicos para desenvolvedor (inicialmente ocultos) -->
    <div id="developer_fields" style="display: none;">
        <div class="mb-3">
            <label for="skills" class="form-label">Principais Habilidades</label>
            <input type="text" class="form-control" id="skills" name="skills" placeholder="Ex: PHP, JavaScript, Laravel">
        </div>
        
        <div class="mb-3">
            <label for="experience" class="form-label">Anos de Experiência</label>
            <select class="form-select" id="experience" name="experience">
                <option value="0-1">0-1 ano</option>
                <option value="1-3">1-3 anos</option>
                <option value="3-5">3-5 anos</option>
                <option value="5+">5+ anos</option>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Telefone</label>
        <input type="tel" class="form-control" id="phone" name="phone" required>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Senha</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirme a Senha</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
</form>

<div class="mt-3 text-center">
    Já tem conta? <a href="{{ route('login') }}">Faça login</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const companyRadio = document.getElementById('type_company');
    const developerRadio = document.getElementById('type_developer');
    const documentLabel = document.getElementById('document_label');
    const nameLabel = document.getElementById('name_label');
    const companyFields = document.getElementById('company_fields');
    const developerFields = document.getElementById('developer_fields');

    function updateFormFields() {
        if (companyRadio.checked) {
            documentLabel.textContent = 'CNPJ';
            nameLabel.textContent = 'Razão Social';
            companyFields.style.display = 'block';
            developerFields.style.display = 'none';
        } else {
            documentLabel.textContent = 'CPF';
            nameLabel.textContent = 'Nome Completo';
            companyFields.style.display = 'none';
            developerFields.style.display = 'block';
        }
    }

    companyRadio.addEventListener('change', updateFormFields);
    developerRadio.addEventListener('change', updateFormFields);
    
    // Inicializa o formulário
    updateFormFields();
});
</script>
@endsection