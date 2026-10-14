<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Actions\CreateSaleAction;
use App\Filament\Resources\Sales\SaleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateSaleAction::class)->handle($data, auth()->user());
    }
}
