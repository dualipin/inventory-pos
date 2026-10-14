<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasColor, HasLabel
{
    case ADMIN = 'admin';
    case CASHIER = 'cajero';
    case INVENTORY = 'inventario';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::CASHIER => 'Cajero',
            self::INVENTORY => 'Inventario',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::ADMIN => 'danger',
            self::CASHIER => 'success',
            self::INVENTORY => 'warning',
        };
    }
}
