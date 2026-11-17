<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroServico extends Model
{
    protected $table = 'registro_servicos';

    protected $fillable = [
        'animal_id',
        'servico_id',
        'forma_pagamento_id',
        'data'
    ];

    protected function casts(): array
    {
        return [
            'data' => 'date'
        ];
    }

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function servico(): BelongsTo
    {
        return $this->belongsTo(Servico::class);
    }

    public function formaPagamento(): BelongsTo
    {
        return $this->belongsTo(FormaPagamento::class);
    }
}
