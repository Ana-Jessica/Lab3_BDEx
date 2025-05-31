@extends('layouts.company')

@section('title', 'Detalhes da Oportunidade')

@section('company-content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>{{ $opportunity->titulo_vaga }}</h4>
        <span class="badge bg-primary">R$ {{ number_format($opportunity->valor_oferta, 2, ',', '.') }}</span>
    </div>
    <div class="card-body">
        <h5>Descrição</h5>
        <p>{{ $opportunity->descricao_vaga }}</p>
        
        <hr>
        
        <h5 class="mt-4">Candidatos</h5>
        @if($opportunity->requests->count() > 0)
            <div class="list-group">
                @foreach($opportunity->requests as $request)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h6>{{ $request->developer->nome_desenvolvedor }}</h6>
                        <small class="text-muted">{{ $request->developer->email_desenvolvedor }}</small>
                    </div>
                    <form method="POST" action="{{ route('company.connections.accept', $request) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Aceitar</button>
                    </form>
                </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">Nenhum candidato ainda.</div>
        @endif
    </div>
</div>
@endsection