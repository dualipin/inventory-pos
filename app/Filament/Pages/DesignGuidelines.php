<?php

namespace App\Filament\Pages;

use App\Enums\UserRole;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class DesignGuidelines extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationLabel = 'Guía de diseño';

    protected static ?string $title = 'Guía de diseño del sistema';

    protected static ?int $navigationSort = 100;

    protected static string|UnitEnum|null $navigationGroup = 'Sistema';

    protected string $view = 'filament.pages.design-guidelines';

    protected Width|string|null $maxContentWidth = Width::Full;

    public float $demoTotal = 145.50;

    public string $demoTender = '200';

    public int $demoStock = 4;

    public int $demoQuantity = 2;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(UserRole::ADMIN) ?? false;
    }

    public function getTitle(): string|Htmlable
    {
        return 'Guía de diseño y componentes';
    }

    public function setDemoTender(float|int $amount): void
    {
        $this->demoTender = number_format((float) $amount, 2, '.', '');
    }

    public function setExactDemoTender(): void
    {
        $this->demoTender = number_format($this->demoTotal, 2, '.', '');
    }

    public function incrementDemoQuantity(): void
    {
        if ($this->demoQuantity < $this->demoStock) {
            $this->demoQuantity++;
        }
    }

    public function decrementDemoQuantity(): void
    {
        if ($this->demoQuantity > 1) {
            $this->demoQuantity--;
        }
    }

    public function toggleDemoStock(): void
    {
        $this->demoStock = $this->demoStock <= 5 ? 24 : 3;
    }

    public function getDemoChangeDueProperty(): float
    {
        $tender = (float) $this->demoTender;

        return max(0.0, round($tender - $this->demoTotal, 2));
    }

    public function getDemoShortageProperty(): float
    {
        $tender = (float) $this->demoTender;

        return max(0.0, round($this->demoTotal - $tender, 2));
    }
}
