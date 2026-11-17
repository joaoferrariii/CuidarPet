<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
     protected $table = 'pets';

    protected $fillable = [
        'cliente_id',
        'nome',
        'raca',
        'data_nascimento',
        'genero',
        'castrado'
    ];

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
            'castrado' => 'boolean'
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function vacinacoes(): HasMany
    {
        return $this->hasMany(Vacinacao::class);
    }

    public function registrosServicos(): HasMany
    {
        return $this->hasMany(RegistroServico::class);
    }
}
