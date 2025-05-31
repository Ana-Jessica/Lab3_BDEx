@extends('layouts.developer')

@section('title', 'Detalhes da Oportunidade')

@section('developer-content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>{{ $opportunity->titulo_vaga }}</h4>
        <span class="badge bg-primary">R$ {{ number_format($opportunity->valor_oferta, 2, ',', '.') }}</span>
    </div>
    <div class="card-body">
        <div class="mb-4">
            <h5>Empresa</h5>
            <p>{{ $opportunity->company->nome_empresa }}</p>
        </div>
        
        <div class="mb-4">
            <h5>Descrição</h5>
            <p>{{ $opportunity->descricao_vaga }}</p>
        </div>
        
        @if(!$hasApplied)
            <form method="POST" action="{{ route('developer.opportunities.apply', $opportunity) }}">
                @csrf
                <button type="submit" class="btn btn-primary">Candidatar-se</button>
            </form>
        @else
            <button class="btn btn-secondary" disabled>Você já se candidatou</button>
        @endif
    </div>
</div>
@endsection