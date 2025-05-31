@extends('layouts.company')

@section('title', 'Dashboard - Empresa')

@section('company-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard</h2>
    <a href="{{ route('company.opportunities.create') }}" class="btn btn-primary">Nova Oportunidade</a>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Oportunidades</h5>
                <p class="card-text display-4">{{ $opportunities }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Conexões</h5>
                <p class="card-text display-4">{{ $connections }}</p>
            </div>
        </div>
    </div>
</div>
@endsection