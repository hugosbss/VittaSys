<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoteProduto extends Model
{
    use HasFactory;

    protected $table = 'lotes_produto';

    protected $fillable = [
        'produto_id',
        'lote',
        'validade',
        'preco_custo',
        'quantidade',
    ];

    protected function casts(): array
    {
        return [
            'validade' => 'date',
            'preco_custo' => 'decimal:2',
            'quantidade' => 'integer',
        ];
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
