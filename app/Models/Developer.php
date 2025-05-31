<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Developer extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'developers';
    protected $primaryKey = 'id_desenvolvedor';
    protected $guard = 'developer';

    protected $fillable = [
        'nome_desenvolvedor',
        'telefone_desenvolvedor',
        'email_desenvolvedor',
        'cpf',
        'linguagens_de_programacao',
        'tecnologias',
        'senha_desenvolvedor'
    ];

    protected $hidden = [
        'senha_desenvolvedor',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'linguagens_de_programacao' => 'array',
        'tecnologias' => 'array',
    ];

    // Relacionamento com solicitações
    public function requests()
    {
        return $this->hasMany(Request::class, 'id_desenvolvedor');
    }

    // Relacionamento com conexões
    public function connections()
    {
        return $this->hasMany(Connection::class, 'id_desenvolvedor');
    }

    // Método para autenticação (sobrescreve o padrão do Laravel)
    public function getAuthPassword()
    {
        return $this->senha_desenvolvedor;
    }
}