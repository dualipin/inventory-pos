<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MovementType: string implements HasColor, HasLabel
{
    case Sale = 'venta';
    case Purchase = 'compra';
    case Adjustment = 'ajuste';
    case ReturnIn = 'devolucion_entrada';
    case Shrinkage = 'merma';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Sale => 'Venta',
            self::Purchase => 'Compra',
            self::Adjustment => 'Ajuste',
            self::ReturnIn => 'Devolución de entrada',
            self::Shrinkage => 'Merma',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Sale => 'danger',
            self::Purchase => 'success',
            self::Adjustment => 'warning',
            self::ReturnIn => 'primary',
            self::Shrinkage => 'secondary',
        };
    }
}
