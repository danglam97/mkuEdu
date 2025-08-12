<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InternationalPostResource\Pages;
use App\Filament\Admin\Resources\InternationalPostResource\RelationManagers;
use App\Models\InternationalPost;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use App\Forms\Components\CKEditor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InternationalPostResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;
    
    protected static ?string $model = InternationalPost::class;

    protected static ?string $modelLabel = 'Tin tức quốc tế';
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $activeNavigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Quản lý menu';
    protected static ?int $navigationSort = 3;

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
                Section::make('Thông tin cơ bản')
                    ->schema([
                        Grid::make(12)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Tiêu đề tin tức')
                                    ->required()
                                    ->maxLength(500)
                                    ->live(onBlur: true)
                                    ->placeholder('Nhập tiêu đề tin tức')
                                    ->columnSpan(12),
                                RichEditor::make('description')
                                    ->label('Mô tả ngắn')
                                    ->maxLength(1000)
                                    ->placeholder('Nhập mô tả ngắn...')
                                    ->columnSpan(12)->toolbarButtons([
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

                                FileUpload::make('image')
                                    ->label('Hình ảnh đại diện')
                                    ->image()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->directory('international-posts')
                                    ->columnSpan(12),

                                TextInput::make('link_url')
                                    ->label('Link tập tin')
                                    ->url()
                                    ->maxLength(550)
                                    ->columnSpan(6),


                                Select::make('slug_category')
                                    ->label('Danh mục')
                                    ->options([
                                        'hop-tac-quoc-te' => 'Hợp tác quốc tế',
                                    ])
                                    ->default('hop-tac-quoc-te')
                                    ->columnSpan(6),
                            ]),
                    ])->collapsible(),

                Section::make('Nội dung bài viết')
                    ->schema([
                        CKEditor::make('contents')
                        ->label('Nội dung tin tức')
                        ->placeholder('Nhập nội dung tin tức...')
                        ->required(),
                    ])->collapsible(),

                Section::make('Cài đặt SEO')
                    ->schema([
                        Grid::make(12)
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->maxLength(100)
                                    ->columnSpan(6),

                                TextInput::make('meta_keyword')
                                    ->label('Meta Keywords')
                                    ->maxLength(500)
                                    ->columnSpan(6),

                                Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->columnSpan(12),
                            ]),
                    ])->collapsible()->collapsed(),

                Section::make('Cài đặt hiển thị')
                    ->schema([
                        Grid::make(12)
                            ->schema([
                                Toggle::make('is_home')
                                    ->label('Hiển thị trang chủ')
                                    ->inline(false)
                                    ->columnSpan(4),

                                Toggle::make('isactive')
                                    ->label('Kích hoạt')
                                    ->inline(false)
                                    ->columnSpan(4),

                                Select::make('status')
                                    ->label('Trạng thái')
                                    ->options([
                                        0 => 'Chờ duyệt',
                                        1 => 'Đã đăng',
                                    ])
                                    ->default(0)
                                    ->columnSpan(4),
                            ]),
                    ])->collapsible()->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Ảnh')
                    ->circular()
                    ->size(50),
                TextColumn::make('name')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(50)->tooltip(fn ($record) => $record->name),
                TextColumn::make('slug_category')
                    ->label('Danh mục')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => match($state) {
                        'hop-tac-quoc-te' => 'Hợp tác quốc tế',
                        default => $state, // fallback tránh lỗi match nếu có giá trị lạ
                    }),
                
                 BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(function ($state) {
                        return (int)$state === 1 ? 'Đã duyệt' : 'Chưa duyệt';
                    }),

                    BadgeColumn::make('is_home')
                        ->label('Trang chủ')
                        ->formatStateUsing(function ($state) {
                            return (int)$state === 1 ? 'Hiển thị' : 'Không';
                        }),

                    BadgeColumn::make('isactive')
                        ->label('Kích hoạt')
                        ->formatStateUsing(function ($state) {
                            return (int)$state === 1 ? 'Hiển thị' : 'Không';
                        }),

                TextColumn::make('created_date')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        1 => 'Đã đăng',
                        1 => 'Chờ đăng',
                    ]),
                Tables\Filters\SelectFilter::make('slug_category')
                    ->label('Danh mục')
                    ->options([
                        'hop-tac-quoc-te' => 'Hợp tác quốc tế',
                    ]),
                Tables\Filters\TernaryFilter::make('is_home')
                    ->label('Hiển thị trang chủ'),
                Tables\Filters\TernaryFilter::make('isactive')
                    ->label('Kích hoạt'),
            ])
            ->defaultSort('created_date', 'desc')
            ->actions([
                Tables\Actions\Action::make('copy_link')
                ->label('Copy link')->iconButton()
                ->icon('heroicon-o-clipboard')
                ->color('gray')
                ->tooltip('Hiển thị đường dẫn để copy')
                ->action(function ($record) {
                    $link = url("/tin-tuc/{$record->slug_category}/{$record->slug}");

                    \Filament\Notifications\Notification::make()
                        ->title('Sao chép link bài viết')
                        ->body("Bạn có thể sao chép đường dẫn sau:<br><strong>{$link}</strong>")
                        ->success()
                        ->send();
                }),

                Tables\Actions\ViewAction::make()
                    ->tooltip('Xem chi tiết')
                    ->iconButton()
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->tooltip('Sửa bài viết')
                    ->iconButton()
                    ->color('primary'),
                Tables\Actions\DeleteAction::make()
                    ->tooltip('Xóa bài viết')
                    ->iconButton()
                    ->color('danger'),
        
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
            'index' => Pages\ListInternationalPosts::route('/'),
            'create' => Pages\CreateInternationalPost::route('/create'),
            'view' => Pages\ViewInternationalPost::route('/{record}'),
            'edit' => Pages\EditInternationalPost::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }
}
