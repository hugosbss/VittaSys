<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caixa extends Model
{
    use HasFactory;

    protected $table = 'caixas';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'valor_abertura',
        'valor_fechamento',
        'aberto_em',
        'fechado_em',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'valor_abertura' => 'decimal:2',
            'valor_fechamento' => 'decimal:2',
            'aberto_em' => 'datetime',
            'fechado_em' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vendas(): HasMany
    {
        return $this->hasMany(Venda::class, 'caixa_id');
    }
}
