<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormaPagamento extends Model
{
    protected $table = 'formas_pagamento';

    protected $fillable = [
        'descricao'
    ];

    public function registrosServicos(): HasMany
    {
        return $this->hasMany(RegistroServico::class);
    }
}
