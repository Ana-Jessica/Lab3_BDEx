@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Meu Perfil</h4>
                <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary">Editar</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; margin: 0 auto;">
                            <i class="bi bi-person-fill" style="font-size: 4rem;"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h3>{{ Auth::user()->nome_empresa ?? Auth::user()->nome_desenvolvedor }}</h3>
                        <p class="text-muted">
                            <i class="bi bi-envelope"></i> {{ Auth::user()->email ?? Auth::user()->email_desenvolvedor }}
                        </p>
                        <p class="text-muted">
                            <i class="bi bi-telephone"></i> {{ Auth::user()->telefone_empresa ?? Auth::user()->telefone_desenvolvedor }}
                        </p>
                        <p class="text-muted">
                            <i class="bi bi-geo-alt"></i> {{ Auth::user()->endereco }}
                        </p>
                        
                        @if(Auth::guard('developer')->check() && Auth::user()->tecnologias)
                        <div class="mt-3">
                            <h5>Tecnologias</h5>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(explode(',', Auth::user()->tecnologias) as $tech)
                                <span class="badge bg-primary">{{ trim($tech) }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection