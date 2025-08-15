<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AlbumResource\Pages;
use App\Filament\Admin\Resources\AlbumResource\RelationManagers;
use App\Models\Album;
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

class AlbumResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;
    protected static ?string $model = Album::class;

    protected static ?string $modelLabel = 'Quản lý Album ảnh';
    protected static ?string $navigationIcon = 'heroicon-s-photo';

    protected static ?string $activeNavigationIcon = 'heroicon-s-photo';
    protected static ?string $navigationGroup = 'Quản lý media';
    protected static ?int $navigationSort = 2;

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
                Forms\Components\Section::make('Thông tin album')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Tên album')
                            ->required()
                            ->placeholder('Nhập tên album')
                            ->minLength(3)
                            ->maxLength(255)
                            ->validationMessages([
                                'required' => 'Vui lòng nhập tên album.',
                                'min' => 'Tên album phải có ít nhất 3 ký tự.',
                                'max' => 'Tên album không được vượt quá 255 ký tự.',
                            ]),

                        Forms\Components\Toggle::make('isactive')
                            ->label('Hiển thị')
                            ->default(true),

                        Forms\Components\Hidden::make('page')
                            ->default('home'),

                        Forms\Components\Hidden::make('position'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Ảnh chính')
                    ->relationship('mainImage') // Liên kết đến bảng con
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Ảnh chính')
                            ->directory('uploads/albums')
                            ->image()
                            ->required()
                            ->validationMessages([
                                'required' => 'Vui lòng chọn ảnh chính.',
                                'mimes' => 'Chỉ chấp nhận file ảnh (jpg, jpeg, png, gif, webp).',
                                'max_size' => 'Kích thước file không được vượt quá 2MB.',
                            ]),

                        Forms\Components\Hidden::make('type')
                            ->default('main'),
                    ]),

                Forms\Components\Section::make('Ảnh phụ')
                    ->schema([
                        Forms\Components\FileUpload::make('sub_images')
                            ->label('Ảnh phụ')
                            ->directory('uploads/albums')
                            ->image()
                            ->multiple()
                            ->maxFiles(20)
                            ->acceptedFileTypes(['image/*'])
                            ->maxSize(2048) // 2MB
                            ->helperText('Kéo thả hoặc chọn nhiều ảnh phụ cùng lúc (tối đa 20 ảnh)')
                            ->required()
                            ->validationMessages([
                                'required' => 'Vui lòng chọn ít nhất một ảnh phụ.',
                                'min' => 'Vui lòng chọn ít nhất một ảnh phụ.',
                                'max' => 'Bạn chỉ có thể chọn tối đa 20 ảnh phụ.',
                                'mimes' => 'Chỉ chấp nhận file ảnh (jpg, jpeg, png, gif, webp).',
                                'max_size' => 'Kích thước file không được vượt quá 2MB.',
                            ]),
                    ]),
            ]);
    }




    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Tên album'),
                Tables\Columns\TextColumn::make('page')->label('Page hiển thị'),
                Tables\Columns\TextColumn::make('position')->label('Vị trí'),
                Tables\Columns\IconColumn::make('isactive')->label('Trạng thái')->boolean(),
                Tables\Columns\TextColumn::make('sub_images_count')
                    ->label('Số ảnh phụ')
                    ->getStateUsing(function ($record) {
                        return $record->items()->where('type', 'sub')->count();
                    }),
//                Tables\Columns\ViewColumn::make('images')
//                    ->label('Hình ảnh')
//                    ->view('admin.album.images'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->tooltip('Sửa')->iconButton(),
                Tables\Actions\DeleteAction::make()->tooltip('Xóa')->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAlbums::route('/'),
            'create' => Pages\CreateAlbum::route('/create'),
            'edit' => Pages\EditAlbum::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }




}
