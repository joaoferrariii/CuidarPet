<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    use HasFactory;

    protected $table = 'pets';

    protected $fillable = [
        'cliente_id',
        'nome',
        'raca',
        'data_nascimento',
        'genero',
        'castrado'
    ];

    protected $appends = ['idade'];

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
            'castrado' => 'boolean'
        ];
    }

    protected function idade(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->data_nascimento) {
                    return null;
                }
                return Carbon::parse($this->data_nascimento)->age . ' anos';
            }
        );
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

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function (Builder $q, string $term) {
            $q->where('nome', 'like', "%{$term}%")
              ->orWhere('raca', 'like', "%{$term}%")
              ->orWhereHas('cliente', function (Builder $cq) use ($term) {
                  $cq->where('nome', 'like', "%{$term}%");
              });
        });
    }
}
