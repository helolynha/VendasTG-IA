<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['pedido_codigo', 'produto_codigo', 'qtd_vendida', 'preco_unitario'])]
class ItemPedido extends Model
{
    use HasFactory;

    protected $table = 'itens_pedidos';

    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_codigo', 'codigo');
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'produto_codigo', 'codigo');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pedido_codigo' => 'integer',
            'produto_codigo' => 'integer',
            'qtd_vendida' => 'integer',
            'preco_unitario' => 'decimal:2',
        ];
    }

    protected function setKeysForSaveQuery($query): Builder
    {
        return $query
            ->where('pedido_codigo', $this->getAttribute('pedido_codigo'))
            ->where('produto_codigo', $this->getAttribute('produto_codigo'));
    }
}
