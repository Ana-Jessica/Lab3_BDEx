@extends('layouts.developer')

@section('title', 'Dashboard - Desenvolvedor')

@section('developer-content')
<h2 class="mb-4">Dashboard</h2>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Conexões</h5>
                <p class="card-text display-4">{{ $connections }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Oportunidades</h5>
                <p class="card-text display-4">{{ $opportunities }}</p>
            </div>
        </div>
    </div>
</div>
@endsection