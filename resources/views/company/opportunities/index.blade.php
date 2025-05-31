@extends('layouts.company')

@section('title', 'Oportunidades')

@section('company-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Oportunidades</h2>
    <a href="{{ route('company.opportunities.create') }}" class="btn btn-primary">Nova Oportunidade</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Valor</th>
                        <th>Candidatos</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opportunities as $opportunity)
                    <tr>
                        <td>{{ $opportunity->titulo_vaga }}</td>
                        <td>R$ {{ number_format($opportunity->valor_oferta, 2, ',', '.') }}</td>
                        <td>{{ $opportunity->requests_count }}</td>
                        <td>
                            <a href="{{ route('company.opportunities.show', $opportunity) }}" class="btn btn-sm btn-info">Ver</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection