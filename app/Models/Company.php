<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Company extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'companies';
    protected $primaryKey = 'id_empresa';
    protected $guard = 'company';

    protected $fillable = [
        'nome_empresa',
        'cnpj',
        'endereco',
        'email',
        'telefone_empresa',
        'senha_empresa'
    ];

    protected $hidden = [
        'senha_empresa',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relacionamento com oportunidades (vagas)
    public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'id_empresa');
    }

    // Relacionamento com conexões
    public function connections()
    {
        return $this->hasMany(Connection::class, 'id_empresa');
    }

    // Método para autenticação (sobrescreve o padrão do Laravel)
    public function getAuthPassword()
    {
        return $this->senha_empresa;
    }
}