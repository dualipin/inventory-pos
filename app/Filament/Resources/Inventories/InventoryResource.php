<?php

namespace App\Filament\Resources\Inventories;

use App\Filament\Resources\Inventories\Pages\CreateInventory;
use App\Filament\Resources\Inventories\Pages\EditInventory;
use App\Filament\Resources\Inventories\Pages\ListInventories;
use App\Filament\Resources\Inventories\Schemas\InventoryForm;
use App\Filament\Resources\Inventories\Tables\InventoriesTable;
use App\Models\Inventory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $modelLabel = 'inventario';

    protected static ?string $pluralModelLabel = 'inventarios';

    protected static ?string $navigationLabel = 'Inventario';

    protected static string|UnitEnum|null $navigationGroup = 'Inventario';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static ?int $navigationSort = -90;

    protected static ?string $recordTitleAttribute = 'product.name';

    public static function getRecordTitle(?Model $record): string|Htmlable|null
    {
        if (! $record instanceof Inventory) {
            return parent::getRecordTitle($record);
        }

        $productName = $record->product?->name;

        if ($productName === null) {
            return parent::getRecordTitle($record);
        }

        return $productName;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('viewAny', Inventory::class) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create', Inventory::class) ?? false;
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
        return auth()->user()?->can('deleteAny', Inventory::class) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return InventoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventories::route('/'),
            'create' => CreateInventory::route('/create'),
            'edit' => EditInventory::route('/{record}/edit'),
        ];
    }
}
