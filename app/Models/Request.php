<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $table = 'requests';
    protected $primaryKey = 'id_solicitacao';

    protected $fillable = [
        'id_desenvolvedor',
        'id_vaga'
    ];

    // Relacionamento com desenvolvedor
    public function developer()
    {
        return $this->belongsTo(Developer::class, 'id_desenvolvedor');
    }

    // Relacionamento com vaga
    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class, 'id_vaga');
    }
}