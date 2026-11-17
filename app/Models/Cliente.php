<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nome',
        'cpf',
        'email',
        'endereco'
    ];

    public function animais(): HasMany
    {
        return $this->hasMany(Animal::class);
    }
}
