<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_conexao',
        'tipo_avaliador',
        'id_avaliador',
        'nota',
        'comentario'
    ];

    // Relacionamento com conexão
    public function connection()
    {
        return $this->belongsTo(Connection::class, 'id_conexao');
    }

    // Método para obter o avaliador (empresa ou desenvolvedor)
    public function evaluator()
    {
        return $this->morphTo(__FUNCTION__, 'tipo_avaliador', 'id_avaliador');
    }
}