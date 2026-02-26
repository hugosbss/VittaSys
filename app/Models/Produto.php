<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'preco',
        'quantidade_estoque',
    ];

    protected $appends = [
        'imagem_url',
    ];

    public function getImagemUrlAttribute(): string
    {
        if (!empty($this->imagem)) {
            return Storage::url($this->imagem);
        }

        return asset('images/produto-placeholder.svg');
    }
}
