<?php

namespace App\Filament\Resources\Sales;

use App\Filament\Resources\Sales\Pages\CreateSale;
use App\Filament\Resources\Sales\Pages\ListSales;
use App\Filament\Resources\Sales\Schemas\SaleForm;
use App\Filament\Resources\Sales\Schemas\SaleInfolist;
use App\Filament\Resources\Sales\Tables\SalesTable;
use App\Models\Sale;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $modelLabel = 'venta';

    protected static ?string $pluralModelLabel = 'ventas';

    protected static ?string $navigationLabel = 'Historial de ventas';

    protected static string|UnitEnum|null $navigationGroup = 'Punto de venta';

    protected static ?int $navigationSort = -100;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static ?string $recordTitleAttribute = 'folio';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user !== null && $user->isCashier()) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('viewAny', Sale::class) ?? false;
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()?->can('view', $record) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create', Sale::class) ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    /**
     * Los administradores cancelan cualquier venta; los cajeros solo las suyas.
     */
    public static function canCancel(?Model $record = null): bool
    {
        if (! $record instanceof Sale) {
            return false;
        }

        return auth()->user()?->can('cancel', $record) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return SaleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SaleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSales::route('/'),
            'create' => CreateSale::route('/create'),
        ];
    }
}
