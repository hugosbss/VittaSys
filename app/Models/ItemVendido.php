<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemVendido extends Model
{
    use HasFactory;

    protected $table = 'itens_vendidos';

    public $timestamps = false;

    protected $fillable = [
        'venda_id',
        'produto_id',
        'lote_produto_id',
        'quantidade',
        'preco_unitario',
        'subtotal',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'quantidade' => 'integer',
            'preco_unitario' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function venda(): BelongsTo
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function loteProduto(): BelongsTo
    {
        return $this->belongsTo(LoteProduto::class, 'lote_produto_id');
    }
}
