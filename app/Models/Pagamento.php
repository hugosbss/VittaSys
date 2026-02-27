<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pagamento extends Model
{
    use HasFactory;

    protected $table = 'pagamentos';

    public $timestamps = false;

    protected $fillable = [
        'venda_id',
        'metodo',
        'valor',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function venda(): BelongsTo
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }
}
