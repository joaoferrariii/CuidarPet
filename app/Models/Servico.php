<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Servico extends Model
{
     protected $table = 'servicos';

    protected $fillable = [
        'descricao'
    ];

    public function registrosServicos(): HasMany
    {
        return $this->hasMany(RegistroServico::class);
    }
}
