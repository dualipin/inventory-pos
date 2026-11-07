<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $modelLabel = 'producto';

    protected static ?string $pluralModelLabel = 'productos';

    protected static ?string $navigationLabel = 'Productos';

    protected static string|UnitEnum|null $navigationGroup = 'Catálogo';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('viewAny', Product::class) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create', Product::class) ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('update', $record) ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('delete', $record) ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->can('deleteAny', Product::class) ?? false;
    }

    public static function canRestore(Model $record): bool
    {
        return auth()->user()?->can('restore', $record) ?? false;
    }

    public static function canRestoreAny(): bool
    {
        return auth()->user()?->can('restoreAny', Product::class) ?? false;
    }

    public static function canForceDelete(Model $record): bool
    {
        return auth()->user()?->can('forceDelete', $record) ?? false;
    }

    public static function canForceDeleteAny(): bool
    {
        return auth()->user()?->can('forceDeleteAny', Product::class) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
