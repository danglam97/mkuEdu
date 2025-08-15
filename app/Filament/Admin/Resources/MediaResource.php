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

    protected static ?string $modelLabel = 'Video';
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
                            ->label('Tiêu đề video')
                            ->placeholder('Nhập tiêu đề video')
                            ->required()
                            ->validationMessages([
                                'required' => 'Vui lòng nhập tiêu đề video',
                            ]),

                        Forms\Components\TextInput::make('url')
                            ->label('Link video')
                            ->placeholder('Ví dụ: https://www.youtube.com/watch?v=xxxx hoặc https://youtu.be/xxxx')
                            ->required()
                            ->rules([
                                'regex:/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+/i'
                            ])
                            ->validationMessages([
                                'regex' => 'Link video phải là link YouTube hợp lệ. Ví dụ: https://www.youtube.com/watch?v=xxxx hoặc https://youtu.be/xxxx',
                                'required' => 'Vui lòng nhập link video YouTube',
                            ]),

                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Ảnh đại diện')
                            ->directory('media/thumbnails')
                            ->image()
                            ->multiple(false)
                            ->maxFiles(1)
                            ->validationMessages([
                                'image' => 'File phải là hình ảnh hợp lệ',
                                'max' => 'Chỉ được upload tối đa 1 ảnh',
                            ]),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Trạng thái hoạt động')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->onIcon('heroicon-s-check')
                            ->offIcon('heroicon-s-x-mark'),

                        Forms\Components\RichEditor::make('description')
                            ->label('Mô tả')
                            ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'strike',
                                        'underline',
                                        'link',
                                        'bulletList',
                                        'orderedList',
                                        'blockquote',
                                        'codeBlock',
                                        'h2',
                                        'h3',
                                        'undo',
                                        'redo',
                                    ]),
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Tiêu đề')->searchable(),
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Hình ảnh')
                    ->disk('public'),
                    Tables\Columns\BadgeColumn::make('is_active') // hoặc 'status'
                    ->label('Trạng thái')
                        ->formatStateUsing(fn ($state) => $state ? 'Hoạt động' : 'Không hoạt động'),

                Tables\Columns\TextColumn::make('url')
                    ->label('Link video')
                    ->getStateUsing(fn ($record) => 'Xem video')
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab()
                    ->icon('heroicon-s-play')
                    ->iconColor('primary')
                    ->tooltip(fn ($record) => $record->url),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Trạng thái hoạt động')
                    ->placeholder('Tất cả')
                    ->trueLabel('Đang hoạt động')
                    ->falseLabel('Không hoạt động'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->tooltip('Xem')
                    ->iconButton()
                    ->icon('heroicon-s-eye')
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->tooltip('Sửa')
                    ->iconButton()
                    ->icon('heroicon-s-pencil')
                    ->color('warning'),
                Tables\Actions\DeleteAction::make()
                    ->tooltip('Xóa')
                    ->iconButton()
                    ->icon('heroicon-s-trash')
                    ->color('danger')
                    ->successNotificationTitle('Xóa media thành công'),
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
