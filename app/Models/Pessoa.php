<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['nome', 'cpf', 'status'])]
class Pessoa extends Model
{
    use HasFactory;

    protected $table = 'pessoas';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'pessoa_codigo', 'codigo');
    }

    public function vendedor(): HasOne
    {
        return $this->hasOne(Vendedor::class, 'pessoa_codigo', 'codigo');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'integer',
        ];
    }
}
