<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opportunity extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'opportunities';
    protected $primaryKey = 'id_vaga';

    protected $fillable = [
        'id_empresa',
        'titulo_vaga',
        'descricao_vaga',
        'valor_oferta'
    ];

    // Relacionamento com empresa
    public function company()
    {
        return $this->belongsTo(Company::class, 'id_empresa');
    }

    // Relacionamento com solicitações
    public function requests()
    {
        return $this->hasMany(Request::class, 'id_vaga');
    }

    // Relacionamento com conexões
    public function connections()
    {
        return $this->hasMany(Connection::class, 'id_vaga');
    }
}