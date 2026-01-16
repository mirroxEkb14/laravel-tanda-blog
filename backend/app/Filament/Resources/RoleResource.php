<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages\ViewRole;
use BezhanSalleh\FilamentShield\Resources\RoleResource as ShieldRoleResource;
use BezhanSalleh\FilamentShield\Resources\RoleResource\Pages\CreateRole;
use BezhanSalleh\FilamentShield\Resources\RoleResource\Pages\EditRole;
use BezhanSalleh\FilamentShield\Resources\RoleResource\Pages\ListRoles;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;

class RoleResource extends ShieldRoleResource
{
    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'view' => ViewRole::route('/{record}'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
