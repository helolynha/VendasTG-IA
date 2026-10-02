<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['descricao', 'preco', 'estoque', 'status', 'categoria_codigo'])]
class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected static function booted(): void
    {
        static::saving(function (Produto $produto): void {
            if ($produto->status !== 3 && $produto->estoque === 0) {
                $produto->status = 2;
            } elseif ($produto->status !== 3
                && $produto->getOriginal('status') === 2
                && $produto->isDirty('estoque')
                && $produto->estoque > 0) {
                $produto->status = 1;
            }
        });
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            1 => 'Ativo',
            2 => 'Em falta',
            3 => 'Inativo',
            default => 'Não informado',
        };
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_codigo', 'codigo');
    }

    public function itensPedidos(): HasMany
    {
        return $this->hasMany(ItemPedido::class, 'produto_codigo', 'codigo');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'preco' => 'decimal:2',
            'estoque' => 'integer',
            'status' => 'integer',
            'categoria_codigo' => 'integer',
        ];
    }
}
