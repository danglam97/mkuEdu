<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AlbumResource\Pages;
use App\Filament\Admin\Resources\AlbumResource\RelationManagers;
use App\Models\Album;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
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
    protected static ?string $navigationIcon = 'heroicon-s-video-camera';

    protected static ?string $activeNavigationIcon = 'heroicon-s-video-camera';
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
                            ->required(),

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
                            ->required(),

                        Forms\Components\Hidden::make('type')
                            ->default('main'),
                    ]),

                Forms\Components\Section::make('Ảnh phụ')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->label('Danh sách ảnh phụ')
                            ->addActionLabel('Thêm hình ảnh con')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label('Ảnh phụ')
                                    ->directory('uploads/albums')
                                    ->image()
                                    ->required(),

                                Forms\Components\Hidden::make('type')
                                    ->default('sub'),

                                Forms\Components\TextInput::make('order')
                                    ->label('Thứ tự')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->minItems(0)
                            ->maxItems(4)
                            ->orderable('order')
                            ->columns(2),
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
}
