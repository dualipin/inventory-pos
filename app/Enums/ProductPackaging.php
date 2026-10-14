<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductPackaging: string implements HasColor, HasLabel
{
    case Bottle = 'botella';
    case TetraPak = 'tetra_pak';
    case Bucket = 'cubeta';
    case Cup = 'vaso';
    case Bag = 'bolsa';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Bottle => 'Botella',
            self::TetraPak => 'Tetra Pak',
            self::Bucket => 'Cubeta',
            self::Cup => 'Vaso',
            self::Bag => 'Bolsa',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Bottle => 'primary',
            self::TetraPak => 'secondary',
            self::Bucket => 'warning',
            self::Cup => 'success',
            self::Bag => 'danger',
        };
    }
}
