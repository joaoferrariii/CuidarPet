<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacinacao extends Model
{
     protected $table = 'vacinacoes';

    protected $fillable = [
        'animal_id',
        'vacina_id',
        'data',
        'validade'
    ];

    protected function casts(): array
    {
        return [
            'data' => 'date',
            'validade' => 'date'
        ];
    }

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function vacina(): BelongsTo
    {
        return $this->belongsTo(Vacina::class);
    }
}
