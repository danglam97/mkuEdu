<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SettingResource\Pages;
use App\Filament\Admin\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Cấu hình';
    protected static ?string $modelLabel = 'Cấu hình';
    protected static ?string $pluralModelLabel = 'Cấu hình';
    protected static ?string $navigationGroup = 'Cấu hình hệ thống';

    protected static ?int $navigationSort = 2;
    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'update',
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('site_name')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('logo')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('favicon')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('footer_text')
                    ->columnSpanFull(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }

}
