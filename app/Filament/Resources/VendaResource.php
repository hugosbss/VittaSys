<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendaResource\Pages;
use App\Models\Produto;
use App\Models\Venda;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VendaResource extends Resource
{
    protected static ?string $model = Venda::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationLabel = 'Relatórios de vendas';

    protected static string|\UnitEnum|null $navigationGroup = 'Relatórios & BI';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['vendedor', 'itens.produto', 'itens.loteProduto', 'pagamentos']))
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Venda')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('vendedor.name')
                    ->label('Vendedor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('caixa_id')
                    ->label('Caixa')
                    ->badge(),
                Tables\Columns\TextColumn::make('forma_pagamento')
                    ->label('Pagamento')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'finalizada' => 'success',
                        'cancelada' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('total_bruto')
                    ->label('Bruto')
                    ->money('BRL')
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('BRL')),
                Tables\Columns\TextColumn::make('desconto')
                    ->label('Desconto')
                    ->money('BRL')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('BRL')),
                Tables\Columns\TextColumn::make('total_liquido')
                    ->label('Líquido')
                    ->money('BRL')
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('BRL')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'aberta' => 'Aberta',
                        'finalizada' => 'Finalizada',
                        'cancelada' => 'Cancelada',
                    ]),
                SelectFilter::make('forma_pagamento')
                    ->label('Forma de pagamento')
                    ->options([
                        'dinheiro' => 'Dinheiro',
                        'pix' => 'Pix',
                        'credito' => 'Crédito',
                        'debito' => 'Débito',
                        'crediario' => 'Crediário',
                    ]),
                SelectFilter::make('produto_id')
                    ->label('Produto')
                    ->options(fn (): array => Produto::query()->orderBy('nome')->pluck('nome', 'id')->all())
                    ->searchable()
                    ->query(function (Builder $query, array $data): Builder {
                        $produtoId = $data['value'] ?? null;

                        if (blank($produtoId)) {
                            return $query;
                        }

                        return $query->whereHas('itens', fn (Builder $itensQuery) => $itensQuery->where('produto_id', $produtoId));
                    }),
                Filter::make('periodo')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('de')->label('De'),
                        \Filament\Forms\Components\DatePicker::make('ate')->label('Até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['de'] ?? null, fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['ate'] ?? null, fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Venda')
                    ->schema([
                        TextEntry::make('id')->label('ID'),
                        TextEntry::make('vendedor.name')->label('Vendedor'),
                        TextEntry::make('caixa_id')->label('Caixa'),
                        TextEntry::make('status')->badge(),
                        TextEntry::make('forma_pagamento')->label('Forma pagamento')->badge(),
                        TextEntry::make('total_bruto')->money('BRL'),
                        TextEntry::make('desconto')->money('BRL'),
                        TextEntry::make('total_liquido')->money('BRL'),
                        TextEntry::make('created_at')->label('Data')->dateTime('d/m/Y H:i'),
                    ])->columns(3),
                Section::make('Itens vendidos')
                    ->schema([
                        RepeatableEntry::make('itens')
                            ->schema([
                                TextEntry::make('produto.nome')->label('Produto'),
                                TextEntry::make('loteProduto.lote')->label('Lote'),
                                TextEntry::make('quantidade')->label('Qtd'),
                                TextEntry::make('preco_unitario')->money('BRL')->label('Unitário'),
                                TextEntry::make('subtotal')->money('BRL')->label('Subtotal'),
                            ])->columns(5),
                    ]),
                Section::make('Pagamentos')
                    ->schema([
                        RepeatableEntry::make('pagamentos')
                            ->schema([
                                TextEntry::make('metodo')->label('Método')->badge(),
                                TextEntry::make('valor')->money('BRL')->label('Valor'),
                                TextEntry::make('created_at')->label('Data')->dateTime('d/m/Y H:i'),
                            ])->columns(3),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendas::route('/'),
            'view' => Pages\ViewVenda::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
