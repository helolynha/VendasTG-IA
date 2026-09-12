<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable(['pessoa_codigo', 'email', 'senha_hash', 'nivel', 'status'])]
#[Hidden(['senha_hash'])]
class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $primaryKey = 'codigo';

    protected $authPasswordName = 'senha_hash';

    public $timestamps = false;

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(Vendedor::class, 'pessoa_codigo', 'pessoa_codigo');
    }

    public function isAdm(): bool
    {
        return $this->nivel === 'adm';
    }

    public function isAtivo(): bool
    {
        return $this->status === 1;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pessoa_codigo' => 'integer',
            'status' => 'integer',
        ];
    }
}
