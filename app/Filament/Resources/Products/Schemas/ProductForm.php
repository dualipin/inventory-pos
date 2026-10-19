<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\ProductPackaging;
use App\Enums\ProductUnit;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Familia de producto')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('name')
                    ->label('Nombre comercial')
                    ->required()
                    ->maxLength(255),
                TextInput::make('barcode')
                    ->label('Código de barras')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Identificador único del producto en el punto de venta.'),
                TextInput::make('price')
                    ->label('Precio')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('$'),
                TextInput::make('cost')
                    ->label('Costo')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('$')
                    ->helperText('Costo de adquisición, usado para calcular el margen.'),
                Select::make('packaging')
                    ->label('Empaque')
                    ->options(ProductPackaging::class)
                    ->required()
                    ->native(false),
                Select::make('unit')
                    ->label('Unidad de medida')
                    ->options(ProductUnit::class)
                    ->required()
                    ->native(false),
                TextInput::make('presentation')
                    ->label('Presentación')
                    ->required()
                    ->maxLength(255),
                TextInput::make('size')
                    ->label('Tamaño')
                    ->required()
                    ->maxLength(255),
                Toggle::make('is_active')
                    ->label('Producto activo')
                    ->helperText('Solo los productos activos se ofrecen en el punto de venta.')
                    ->default(true),
                SpatieMediaLibraryFileUpload::make('image')
                    ->label('Imagen del producto')
                    ->collection('image')
                    ->image()
                    ->conversion('thumb')
                    ->maxSize(5120)
                    ->panelLayout('integrated')
                    ->helperText('Se permite una imagen por producto. Se genera una miniatura de 400 px para el catálogo.')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
