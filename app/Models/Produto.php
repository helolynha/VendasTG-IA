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
