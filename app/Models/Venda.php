<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venda extends Model
{
    use HasFactory;

    protected $table = 'vendas';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'caixa_id',
        'total_bruto',
        'desconto',
        'total_liquido',
        'forma_pagamento',
        'status',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'total_bruto' => 'decimal:2',
            'desconto' => 'decimal:2',
            'total_liquido' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function caixa(): BelongsTo
    {
        return $this->belongsTo(Caixa::class, 'caixa_id');
    }

    public function itens(): HasMany
    {
        return $this->hasMany(ItemVendido::class, 'venda_id');
    }

    public function pagamentos(): HasMany
    {
        return $this->hasMany(Pagamento::class, 'venda_id');
    }
}
