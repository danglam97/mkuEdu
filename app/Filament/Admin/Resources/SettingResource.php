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
    protected static ?string $navigationLabel = 'Cấu hình website';
    protected static ?string $modelLabel = 'Cấu hình website';
    protected static ?string $pluralModelLabel = 'Cấu hình website';
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
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->description('Cấu hình thông tin chung của website')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name_uni')
                                    ->label('Tên trường')
                                    ->required()
                                    ->maxLength(500)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Tên trường đại học')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('name_sologan')
                                    ->label('Slogan')
                                    ->maxLength(500)
                                    ->placeholder('Slogan của trường')
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả ngắn')
                            ->maxLength(1000)
                            ->placeholder('Mô tả ngắn về trường')
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Hình ảnh & Logo')
                    ->description('Logo và favicon của website')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('logo')
                                    ->label('Logo')
                                    ->image()
                                    ->imageEditor()
                                    ->imageCropAspectRatio('16:9')
                                    ->imageResizeTargetWidth('400')
                                    ->imageResizeTargetHeight('225')
                                    ->placeholder('Chọn logo')
                                    ->columnSpan(1),

                                Forms\Components\FileUpload::make('favicon')
                                    ->label('Favicon')
                                    ->image()
                                    ->imageEditor()
                                    ->imageCropAspectRatio('1:1')
                                    ->imageResizeTargetWidth('32')
                                    ->imageResizeTargetHeight('32')
                                    ->placeholder('Chọn favicon')
                                    ->columnSpan(1),
                            ]),
                    ]),

                Forms\Components\Section::make('Thông tin liên hệ')
                    ->description('Email, điện thoại và địa chỉ')
                    ->icon('heroicon-o-envelope')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(550)
                                    ->placeholder('contact@example.com')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('phone')
                                    ->label('Điện thoại')
                                    ->maxLength(550)
                                    ->placeholder('0123456789; 0987654321')
                                    ->helperText('Nhập nhiều số điện thoại thì cách nhau bằng dấu ;')
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Textarea::make('address')
                            ->label('Địa chỉ')
                            ->placeholder('Địa chỉ trường')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Cài đặt khác')
                    ->description('Link URL và trạng thái')
                    ->icon('heroicon-o-cog')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('link_url')
                                    ->label('Link URL')
                                    ->url()
                                    ->maxLength(550)
                                    ->placeholder('https://example.com')
                                    ->columnSpan(1),

                                Forms\Components\Toggle::make('isactive')
                                    ->label('Trạng thái hiển thị')
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->onIcon('heroicon-s-check')
                                    ->offIcon('heroicon-s-x-mark')
                                    ->columnSpan(1),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->square()
                    ->size(50),

                Tables\Columns\TextColumn::make('name_uni')
                    ->label('Tên trường')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('name_sologan')
                    ->label('Slogan')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Điện thoại')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone'),

                Tables\Columns\BadgeColumn::make('isactive')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn ($state) => $state ? 'Hoạt động' : 'Không hoạt động')
                    ->colors([
                        'success' => 'Hoạt động',
                        'danger' => 'Không hoạt động',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Cập nhật lần cuối')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->tooltip('Sửa cấu hình')
                    ->icon('heroicon-o-pencil-square')
                    ->iconButton(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? static::getModel()::count()
            : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
