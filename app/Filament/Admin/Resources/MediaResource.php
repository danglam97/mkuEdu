<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MediaResource\Pages;
use App\Filament\Admin\Resources\MediaResource\RelationManagers;
use App\Models\Media;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MediaResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;
    protected static ?string $model = Media::class;

    protected static ?string $modelLabel = 'Media';
    protected static ?string $navigationIcon = 'heroicon-s-video-camera';

    protected static ?string $activeNavigationIcon = 'heroicon-s-video-camera';
    protected static ?string $navigationGroup = 'Quản lý media';
    protected static ?int $navigationSort = 1;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\Grid::make(1)->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Tiêu đề')
                            ->placeholder('nhập tiêu đề media')
                            ->required(),

                        Forms\Components\Select::make('type')
                            ->label('Loại media')
                            ->options([
                                'image' => 'Ảnh',
                                'video' => 'Video',
                            ])
                            ->reactive()
                            ->required(),

                        Forms\Components\Select::make('source')
                            ->label('Nguồn video')
                            ->options([
                                'file' => 'Tải lên file (.mp4)',
                                'link' => 'Link YouTube / Vimeo',
                            ])
                            ->visible(fn ($get) => $get('type') === 'video')
                            ->requiredIf('type', 'video')
                            ->reactive(),

                        Forms\Components\FileUpload::make('url')
                            ->label('Tải lên ảnh hoặc video')
                            ->directory('media')
                            ->visible(fn ($get) =>
                                ($get('type') === 'image') || ($get('type') === 'video' && $get('source') === 'file')
                            )
                            ->acceptedFileTypes(['image/*', 'video/mp4'])
                            ->required(fn ($get) =>
                                ($get('type') === 'image') || ($get('type') === 'video' && $get('source') === 'file')
                            ),

                        Forms\Components\TextInput::make('url')
                            ->label('Link video')
                            ->visible(fn ($get) => $get('type') === 'video' && $get('source') === 'link')
                            ->required(fn ($get) => $get('type') === 'video' && $get('source') === 'link'),

                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Ảnh đại diện (thumbnail)')
                            ->directory('media/thumbnails')
                            ->image()
                            ->imageEditor()
                            ->visible(fn ($get) => $get('type') === 'video'),

                        Forms\Components\Select::make('page')
                            ->label('Trang hiển thị')
                            ->options([
                                'home' => 'Trang chủ',
                                'about' => 'Giới thiệu',
                                'contact' => 'Liên hệ',
                                'blog' => 'Blog',
                            ])
                            ->searchable(),

                        Forms\Components\TextInput::make('position')
                            ->placeholder('nhập vị trí')
                            ->label('Vị trí (ví dụ: top_banner, footer)')
                            ->nullable(),

                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3),
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Tiêu đề')->searchable(),
                Tables\Columns\TextColumn::make('type')->label('Loại'),
                Tables\Columns\TextColumn::make('page')->label('Trang'),
                Tables\Columns\ImageColumn::make('preview')
                    ->label('Hiển thị')
                    ->getStateUsing(function ($record) {
                        return $record->type === 'video' ? $record->thumbnail : $record->url;
                    })
                    ->circular() // tùy chọn: bo tròn ảnh
                    ->height(60)
                    ->width(100),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->tooltip('Sửa')->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton()->tooltip('Xóa')->successNotificationTitle('Xóa media thành công'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
            ]);
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
            'index' => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];

    }
    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }
}
