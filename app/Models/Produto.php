<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'sku',
        'categoria',
        'descricao',
        'imagem',
    ];

    protected $appends = [
        'imagem_url',
        'preco_custo_atual',
        'estoque_atual',
        'lote_atual',
        'validade_atual',
        'validade_atual_formatada',
    ];

    protected $with = [
        'loteAtual',
    ];

    public function lotes(): HasMany
    {
        return $this->hasMany(LoteProduto::class, 'produto_id');
    }

    public function loteAtual(): HasOne
    {
        return $this->hasOne(LoteProduto::class, 'produto_id')->latestOfMany();
    }

    public function getImagemUrlAttribute(): string
    {
        if (!empty($this->imagem)) {
            return Storage::url($this->imagem);
        }

        return asset('images/produto-placeholder.svg');
    }

    public function getPrecoCustoAtualAttribute(): ?float
    {
        return $this->obterLoteAtual()?->preco_custo;
    }

    public function getEstoqueAtualAttribute(): int
    {
        return (int) ($this->obterLoteAtual()?->quantidade ?? 0);
    }

    public function getLoteAtualAttribute(): ?string
    {
        return $this->obterLoteAtual()?->lote;
    }

    public function getValidadeAtualAttribute(): ?string
    {
        return $this->obterLoteAtual()?->validade?->format('Y-m-d');
    }

    public function getValidadeAtualFormatadaAttribute(): ?string
    {
        return $this->obterLoteAtual()?->validade?->format('d/m/Y');
    }

    private function obterLoteAtual(): ?LoteProduto
    {
        if ($this->relationLoaded('loteAtual')) {
            /** @var LoteProduto|null $lote */
            $lote = $this->getRelation('loteAtual');

            return $lote;
        }

        return $this->loteAtual()->first();
    }
}
