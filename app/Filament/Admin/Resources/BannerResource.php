<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BannerResource\Pages;
use App\Filament\Admin\Resources\BannerResource\RelationManagers;
use App\Models\Banner;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BannerResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;
    protected static ?string $model = Banner::class;

    protected static ?string $modelLabel = 'Quản lý Banner';
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $activeNavigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Quản lý media';
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
                Section::make('Thông tin banner')
                    ->schema([
                        Grid::make(12)
                            ->schema([
                                TextInput::make('title')
                                    ->label('Tiêu đề')
                                    ->maxLength(255)
                                    ->columnSpan(6)
                                    ->placeholder('tiêu đề banner'),

                                TextInput::make('link')
                                    ->label('Liên kết')
                                    ->url()
                                    ->maxLength(255)
                                    ->columnSpan(6)->placeholder('link liên kết'),

                                Select::make('position')
                                    ->label('Vị trí hiển thị')
                                    ->options([
                                        'home_top' => 'Trang chủ - Phần trên',
                                        'home_slider' => 'Trang chủ - Slider',
                                        'sidebar' => 'Thanh bên',
                                        'footer' => 'Chân trang',
                                        'news_top' => 'Tin tức - Phần trên',
                                        'news_sidebar' => 'Tin tức - Thanh bên',
                                    ])
                                    ->required()
                                    ->columnSpan(6),

                                Toggle::make('is_active')
                                    ->label('Kích hoạt')
                                    ->inline(false)
                                    ->columnSpan(6),

                                FileUpload::make('image')
                                    ->label('Ảnh')
                                    ->image()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->directory('banners')
                                    ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/jpeg', 'image/webp'])
                                    ->columnSpan(12),

                                RichEditor::make('description')
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
                                    ])
                                    ->columnSpan(12),
                            ]),
                    ])->collapsible(),
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
                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(30),
                TextColumn::make('position')
                    ->label('Vị trí')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                BadgeColumn::make('is_active')
                    ->label('Trạng thái')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Đang hiển thị' : 'Đã ẩn')
                    ->icon(fn (bool $state): string => $state ? 'heroicon-o-eye' : 'heroicon-o-eye-slash'),
            ])
            ->filters([
                
            ])
            ->defaultSort('order', 'asc')
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->tooltip('Xem chi tiết')
                    ->iconButton()
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->tooltip('Sửa banner')
                    ->iconButton()
                    ->color('primary'),
                Tables\Actions\DeleteAction::make()
                    ->tooltip('Xóa banner')
                    ->iconButton()
                    ->color('danger'),
                Action::make('move_up')
                    ->label('Di chuyển lên')
                    ->icon('heroicon-o-arrow-up')
                    ->color('success')
                    ->iconButton()
                    ->tooltip('Di chuyển lên trên')
                    ->action(function (Banner $record) {
                        $previousBanner = Banner::where('position', $record->position)
                            ->where('order', '<', $record->order)
                            ->orderBy('order', 'desc')
                            ->first();
                        
                        if ($previousBanner) {
                            $tempOrder = $record->order;
                            $record->update(['order' => $previousBanner->order]);
                            $previousBanner->update(['order' => $tempOrder]);
                        }
                    })
                    ->visible(fn (Banner $record) => $record->order > 1),
                
                Action::make('move_down')
                    ->label('Di chuyển xuống')
                    ->icon('heroicon-o-arrow-down')
                    ->color('warning')
                    ->iconButton()
                    ->tooltip('Di chuyển xuống dưới')
                    ->action(function (Banner $record) {
                        $nextBanner = Banner::where('position', $record->position)
                            ->where('order', '>', $record->order)
                            ->orderBy('order')
                            ->first();
                        
                        if ($nextBanner) {
                            $tempOrder = $record->order;
                            $record->update(['order' => $nextBanner->order]);
                            $nextBanner->update(['order' => $tempOrder]);
                        }
                    })
                    ->visible(fn (Banner $record) => $record->order < Banner::where('position', $record->position)->max('order')),
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'view' => Pages\ViewBanner::route('/{record}'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }
}
