<?php

namespace App\Filament\Resources\VendaResource\Pages;

use App\Filament\Resources\VendaResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListVendas extends ListRecords
{
    protected static string $resource = VendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('imprimir')
                ->label('Imprimir relatório')
                ->icon('heroicon-o-printer')
                ->url(fn (): string => route('admin.relatorios.vendas.imprimir', request()->query()))
                ->openUrlInNewTab(),
        ];
    }
}
