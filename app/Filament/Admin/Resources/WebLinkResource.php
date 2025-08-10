<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WebLinkResource\Pages;
use App\Models\WebLink;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;

class WebLinkResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;
    protected static ?string $model = WebLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'Quản lý liên kết';
    protected static ?string $modelLabel = 'Liên kết website';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin liên kết')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Tên liên kết')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Nhập tên liên kết'),

                                Forms\Components\TextInput::make('url')
                                    ->label('Đường dẫn')
                                    ->url()
                                    ->required()
                                    ->maxLength(500)
                                    ->placeholder('https://...'),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->maxLength(500)
                            ->rows(3)
                            ->placeholder('Nhập mô tả ngắn cho liên kết...'),
                    ])
                    ->columnSpanFull(),

                Forms\Components\Section::make('Hình ảnh & trạng thái')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Ảnh đại diện')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('weblinks/images')
                            ->imagePreviewHeight('150')
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Kích hoạt')
                            ->default(true)
                            ->columnSpan(1),
                    ])
                    ->columns(1)
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Ảnh')
                    ->square()
                    ->size(50) // Tăng nhẹ kích thước cho rõ hơn
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tên liên kết')
                    ->searchable(),

                Tables\Columns\IconColumn::make('url')
                    ->label('Link')
                    ->icon('heroicon-o-link')
                    ->url(fn ($record) => $record->url, true) // true => mở tab mới
                    ->tooltip(fn ($record) => $record->url),

                BadgeColumn::make('is_active')
                    ->label('Trạng thái')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Đang hiển thị' : 'Đã ẩn')
                    ->icon(fn (bool $state): string => $state ? 'heroicon-o-eye' : 'heroicon-o-eye-slash'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->iconButton()->tooltip('Xem'),
                Tables\Actions\EditAction::make()->iconButton()->tooltip('Sửa'),
                Tables\Actions\DeleteAction::make()->iconButton()->tooltip('Xóa'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWebLinks::route('/'),
            'create' => Pages\CreateWebLink::route('/create'),
            'edit'   => Pages\EditWebLink::route('/{record}/edit'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }
}
