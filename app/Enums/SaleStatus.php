<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SaleStatus: string implements HasColor, HasLabel
{
    case Completed = 'completada';
    case Cancelled = 'cancelada';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Completed => 'Completada',
            self::Cancelled => 'Cancelada',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Completed => 'success',
            self::Cancelled => 'danger',
        };
    }
}
