<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacinacao extends Model
{
    use HasFactory;

    protected $table = 'vacinacoes';

    protected $fillable = [
        'pet_id',
        'vacina_id',
        'data',
        'validade'
    ];

    protected function casts(): array
    {
        return [
            'data' => 'date:d/m/Y',
            'validade' => 'date:d/m/Y'
        ];
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function vacina(): BelongsTo
    {
        return $this->belongsTo(Vacina::class);
    }
}
