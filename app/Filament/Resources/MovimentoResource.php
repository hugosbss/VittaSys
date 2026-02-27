<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovimentoResource\Pages;
use App\Models\ItemVendido;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MovimentoResource extends Resource
{
    protected static ?string $model = ItemVendido::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-receipt-refund';

    protected static ?string $navigationLabel = 'Listagem de movimentos';

    protected static string|\UnitEnum|null $navigationGroup = 'Relatórios & BI';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['venda', 'produto', 'loteProduto']))
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Movimento')->sortable(),
                Tables\Columns\TextColumn::make('venda.id')->label('Venda')->badge()->sortable(),
                Tables\Columns\TextColumn::make('produto.nome')->label('Produto')->searchable(),
                Tables\Columns\TextColumn::make('loteProduto.lote')->label('Lote')->searchable(),
                Tables\Columns\TextColumn::make('quantidade')->label('Qtd')->sortable(),
                Tables\Columns\TextColumn::make('preco_unitario')->label('Unitário')->money('BRL'),
                Tables\Columns\TextColumn::make('subtotal')->label('Subtotal')->money('BRL')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('BRL')),
                Tables\Columns\TextColumn::make('created_at')->label('Data')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status_venda')
                    ->label('Status da venda')
                    ->relationship('venda', 'status'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMovimentos::route('/'),
            'view' => Pages\ViewMovimento::route('/{record}'),
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
