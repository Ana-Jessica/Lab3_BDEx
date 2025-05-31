@extends('layouts.developer')

@section('title', 'Oportunidades')

@section('developer-content')
<h2 class="mb-4">Oportunidades</h2>

<div class="row">
    @foreach($opportunities as $opportunity)
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">{{ $opportunity->titulo_vaga }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">{{ $opportunity->company->nome_empresa }}</h6>
                <p class="card-text">{{ Str::limit($opportunity->descricao_vaga, 150) }}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-primary">R$ {{ number_format($opportunity->valor_oferta, 2, ',', '.') }}</span>
                    @if($hasApplied->contains($opportunity->id_vaga))
                        <button class="btn btn-sm btn-secondary" disabled>Já candidatado</button>
                    @else
                        <form method="POST" action="{{ route('developer.opportunities.apply', $opportunity) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">Candidatar-se</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection