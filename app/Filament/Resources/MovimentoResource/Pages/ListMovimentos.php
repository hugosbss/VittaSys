<?php

namespace App\Filament\Resources\MovimentoResource\Pages;

use App\Filament\Resources\MovimentoResource;
use Filament\Resources\Pages\ListRecords;

class ListMovimentos extends ListRecords
{
    protected static string $resource = MovimentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('imprimir')
                ->label('Imprimir relatório')
                ->icon('heroicon-o-printer')
                ->url(fn (): string => route('admin.relatorios.movimentos.imprimir', request()->query()))
                ->openUrlInNewTab(),
        ];
    }
}
