<?php

namespace App\Filament\Resources\InventoryMovements;

use App\Enums\UserRole;
use App\Filament\Resources\InventoryMovements\Pages\CreateInventoryMovement;
use App\Filament\Resources\InventoryMovements\Pages\ListInventoryMovements;
use App\Filament\Resources\InventoryMovements\Schemas\InventoryMovementForm;
use App\Filament\Resources\InventoryMovements\Tables\InventoryMovementsTable;
use App\Models\InventoryMovement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class InventoryMovementResource extends Resource
{
    protected static ?string $model = InventoryMovement::class;

    protected static ?string $modelLabel = 'movimiento de inventario';

    protected static ?string $pluralModelLabel = 'movimientos de inventario';

    protected static ?string $navigationLabel = 'Movimientos';

    protected static string|UnitEnum|null $navigationGroup = 'Inventario';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static ?int $navigationSort = -80;

    protected static ?string $recordTitleAttribute = 'description';

    public static function getRecordTitle(?Model $record): string|Htmlable|null
    {
        if (! $record instanceof InventoryMovement) {
            return parent::getRecordTitle($record);
        }

        if (filled($record->description)) {
            return $record->description;
        }

        $productName = $record->inventory?->product?->name;

        if ($productName === null) {
            return parent::getRecordTitle($record);
        }

        return sprintf('%s — %s', $productName, $record->type->getLabel());
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(UserRole::ADMIN, UserRole::INVENTORY) ?? false;
    }

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return InventoryMovementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoryMovementsTable::configure($table);
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
            'index' => ListInventoryMovements::route('/'),
            'create' => CreateInventoryMovement::route('/create'),
        ];
    }
}
