<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['pessoa_codigo', 'nivel_fidelidade'])]
class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

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
        return $this->hasMany(Pedido::class, 'cliente_codigo', 'pessoa_codigo');
    }

    public function nivelFidelidadeLabel(): string
    {
        return match ($this->nivel_fidelidade) {
            1 => 'Bronze',
            2 => 'Prata',
            3 => 'Ouro',
            default => (string) $this->nivel_fidelidade,
        };
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pessoa_codigo' => 'integer',
            'nivel_fidelidade' => 'integer',
        ];
    }
}
