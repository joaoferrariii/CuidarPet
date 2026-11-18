<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroServico extends Model
{
    use HasFactory;

    protected $table = 'registros_servicos';

    protected $fillable = [
        'pet_id',
        'servico_id',
        'forma_pagamento_id',
        'data'
    ];

    protected function casts(): array
    {
        return [
            'data' => 'date:d/m/Y'
        ];
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
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
