<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vacina extends Model
{
    protected $table = 'vacinas';

    protected $fillable = [
        'descricao'
    ];

    public function vacinacoes(): HasMany
    {
        return $this->hasMany(Vacinacao::class);
    }
}
