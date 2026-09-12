<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['pessoa_codigo', 'salario', 'comissao'])]
class Vendedor extends Model
{
    use HasFactory;

    protected $table = 'vendedores';

    protected $primaryKey = 'pessoa_codigo';

    protected $keyType = 'int';

    public $incrementing = false;

    public $timestamps = false;

    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_codigo', 'codigo');
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'vendedor_codigo', 'pessoa_codigo');
    }

    public function usuario(): HasOne
    {
        return $this->hasOne(Usuario::class, 'pessoa_codigo', 'pessoa_codigo');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pessoa_codigo' => 'integer',
            'salario' => 'decimal:2',
            'comissao' => 'decimal:2',
        ];
    }
}
