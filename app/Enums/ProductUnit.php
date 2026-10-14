<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductUnit: string implements HasColor, HasLabel
{
    case Milliliter = 'ml';
    case Liter = 'L';
    case Gram = 'g';
    case Kilogram = 'kg';
    case Piece = 'pieza';

    public function getLabel(): string
    {
        return match ($this) {
            self::Milliliter => 'Mililitro',
            self::Liter => 'Litro',
            self::Gram => 'Gramo',
            self::Kilogram => 'Kilogramo',
            self::Piece => 'Pieza',
        };
    }

    public function getAbbreviation(): string
    {
        return match ($this) {
            self::Milliliter => 'ml',
            self::Liter => 'L',
            self::Gram => 'g',
            self::Kilogram => 'kg',
            self::Piece => 'pza',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Milliliter => 'primary',
            self::Liter => 'secondary',
            self::Gram => 'success',
            self::Kilogram => 'warning',
        };
    }
}
