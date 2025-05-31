<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Connection extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'connections';
    protected $primaryKey = 'id_conexao';

    protected $fillable = [
        'id_empresa',
        'id_desenvolvedor',
        'data_conexao'
    ];

    // Relacionamento com empresa
    public function company()
    {
        return $this->belongsTo(Company::class, 'id_empresa');
    }

    // Relacionamento com desenvolvedor
    public function developer()
    {
        return $this->belongsTo(Developer::class, 'id_desenvolvedor');
    }

    // Relacionamento com avaliações
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'id_conexao');
    }
}