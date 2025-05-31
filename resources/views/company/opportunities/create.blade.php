@extends('layouts.company')

@section('title', 'Criar Oportunidade')

@section('company-content')
<div class="card">
    <div class="card-header">
        <h4>Criar Nova Oportunidade</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('company.opportunities.store') }}">
            @csrf
            <div class="mb-3">
                <label for="titulo_vaga" class="form-label">Título da Vaga</label>
                <input type="text" class="form-control" id="titulo_vaga" name="titulo_vaga" required>
            </div>
            <div class="mb-3">
                <label for="descricao_vaga" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao_vaga" name="descricao_vaga" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label for="valor_oferta" class="form-label">Valor Ofertado</label>
                <input type="number" step="0.01" class="form-control" id="valor_oferta" name="valor_oferta">
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</div>
@endsection