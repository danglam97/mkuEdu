<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PostResource\Pages;
use App\Filament\Admin\Resources\PostResource\RelationManagers;
use App\Forms\Components\CKEditor;
use App\Models\Menus;
use App\Models\Post;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $modelLabel = 'Bài viết';
    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $activeNavigationIcon = 'heroicon-s-folder';
    protected static ?string $navigationGroup = 'Quản lý menu';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tạo/Cập nhật Tin tức')
                    ->columnSpanFull()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Thông tin chính')
                            ->schema([
                                Forms\Components\Grid::make(1)->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Tên tin tức')
                                        ->required()
                                        ->maxLength(500),

                                    Forms\Components\Textarea::make('description')
                                        ->label('Mô tả ngắn')
                                        ->rows(3),

                                    Forms\Components\FileUpload::make('image')
                                        ->label('Hình ảnh đại diện')
                                        ->image()
                                        ->directory('news/images')
                                        ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp']),

                                    Forms\Components\Select::make('id_category')
                                        ->label('Danh mục tin')
                                        ->options(self::getCategoryOptions()) // sửa theo mối quan hệ của bạn
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                    CKEditor::make('contents')
                                        ->label('Nội dung tin tức')->required(),
                                    Forms\Components\TextInput::make('link_url')
                                        ->label('Link tập tin')
                                        ->maxLength(550),

                                    Forms\Components\Toggle::make('is_home')
                                        ->label('Hiển thị lên trang chủ')
                                        ->inline(),
                                ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Thông tin SEO')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('meta_title')
                                        ->label('Meta Title')
                                        ->maxLength(100),

                                    Forms\Components\TextInput::make('meta_keyword')
                                        ->label('Meta Keyword')
                                        ->maxLength(500),
                                ]),

                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
    public static function  getCategoryOptions($categories = null, $prefix = ''): array
    {
        $categories = $categories ?? Menus::whereNull('id_parent')->with('children')->get();

        $result = [];

        foreach ($categories as $category) {
            $result[$category->id] = $prefix . $category->name;

            if ($category->children->count()) {
                $result += self::getCategoryOptions($category->children, $prefix . '— ');
            }
        }

        return $result;
    }
    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }
}
