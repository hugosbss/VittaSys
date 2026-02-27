<?php

namespace App\Filament\Resources\MovimentoResource\Pages;

use App\Filament\Resources\MovimentoResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewMovimento extends ViewRecord
{
    protected static string $resource = MovimentoResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Movimento')
                ->schema([
                    TextEntry::make('id')->label('ID'),
                    TextEntry::make('venda.id')->label('Venda'),
                    TextEntry::make('produto.nome')->label('Produto'),
                    TextEntry::make('loteProduto.lote')->label('Lote'),
                    TextEntry::make('quantidade')->label('Quantidade'),
                    TextEntry::make('preco_unitario')->label('Preço unitário')->money('BRL'),
                    TextEntry::make('subtotal')->label('Subtotal')->money('BRL'),
                    TextEntry::make('created_at')->label('Data')->dateTime('d/m/Y H:i'),
                ])->columns(2),
        ]);
    }
}
