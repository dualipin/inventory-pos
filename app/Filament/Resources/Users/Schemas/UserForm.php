<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Correo electrónico')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('role')
                    ->label('Responsabilidad')
                    ->options(UserRole::class)
                    ->required()
                    ->native(false)
                    ->disabled(fn (?User $record): bool => $record !== null && ($record->id === auth()->id() || ! (auth()->user()?->can('changeRole', $record) ?? false)))
                    ->helperText(fn (?User $record): ?string => $record !== null && $record->id === auth()->id()
                        ? 'No puedes cambiar tu propio rol de administrador.'
                        : null),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->revealable()
                    ->rule(Password::defaults())
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->maxLength(255),
            ])
            ->columns(2);
    }
}
