<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nome',
        'cpf',
        'email',
        'endereco'
    ];

    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function (Builder $q, string $term) {
            $q->where('nome', 'like', "%{$term}%")
              ->orWhere('cpf', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    protected function cpf(): Attribute
    {
        return Attribute::make(
            get: function (?string $value) {
                if (!$value) return '';
                $digits = preg_replace('/\D/', '', $value);
                if (strlen($digits) === 11) {
                    return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $digits);
                }
                return $value;
            }
        );
    }
}
