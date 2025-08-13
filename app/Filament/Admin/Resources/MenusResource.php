<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MenusResource\Pages;
use App\Filament\Admin\Resources\MenusResource\RelationManagers;
use App\Models\Menus;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenusResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;

    protected static ?string $model = Menus::class;

    protected static ?string $modelLabel = 'Menus';
    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $activeNavigationIcon = 'heroicon-s-folder';
    protected static ?string $navigationGroup = 'Quản lý menu';
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
                Forms\Components\Tabs::make('Tạo/Cập nhật menu')
                    ->columnSpanFull()
                    ->tabs([
                        // Tab 1: Thông tin chính
                        Forms\Components\Tabs\Tab::make('Thông tin chính')
                            ->schema([
                                Forms\Components\Grid::make(1)
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Tên menu')
                                            ->placeholder('Nhập tên menu')
                                            ->markAsRequired()
                                            ->rules([
                                                'required',
                                                'max:255',
                                                fn($record) => $record
                                                    ? "unique:menus,name,{$record->id}"
                                                    : 'unique:menus,name',
                                            ])
                                            ->validationMessages([
                                                'required' => 'Tên menu không được để trống.',
                                                'max' => 'Tên menu không được vượt quá :max ký tự.',
                                                'unique' => 'Tên menu đã tồn tại.',
                                            ]),
                                    ]),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('type')
                                            ->label('Loại menu')
                                            ->options([
                                                0 => 'Liên kết',
                                                1 => 'Nội dung',
                                            ])
                                            ->required()
                                            ->validationMessages([
                                                'required' => 'loại menu không được để trống.',
                                            ])
                                            ->columnSpan(fn (callable $get) => $get('type') == 0 ? 2 : 1)
                                            ->reactive()
                                            ->native(false)
                                            ->placeholder('Chọn loại menu'),

                                        Forms\Components\Select::make('id_parent')
                                            ->label('menu cha')
                                            ->options(\App\Models\Menus::groupedCategories())
                                            ->searchable()
                                            ->preload()
                                            ->placeholder('Chọn menu cha')
                                            ->visible(fn (callable $get) => $get('type') != 0),
                                    ]),
                                Forms\Components\TextInput::make('url')
                                    ->label('Liên kết menu')
                                    ->placeholder('Nhập đường dẫn liên kết')
                                    ->visible(fn (callable $get) => $get('type') == 0)
                                    ->required(fn (callable $get) => $get('type') == 0)
                                    ->rule('url')
                                    ->validationMessages([
                                        'required' => 'Đường dẫn liên kết là bắt buộc khi loại menu là Liên kết.',
                                        'url' => 'Đường dẫn không hợp lệ (phải có dạng https://...)',
                                    ]),

                                Forms\Components\FileUpload::make('icon')
                                    ->label('Icon')
                                    ->image()
                                    ->disk('public')
                                    ->directory('menues/images')
                                    ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/jpeg', 'image/webp'])
                                    ->imageEditor(),

                                Forms\Components\RichEditor::make('notes')
                                    ->label('Ghi chú')
                                    ->placeholder('Nhập ghi chú')
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

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Kích hoạt')
                                            ->default(true),
                                        Forms\Components\Toggle::make('is_showPosition')
                                            ->label('Hiển thị vị trí')
                                            ->default(true),
                                    ]),

                                Forms\Components\TextInput::make('position')
                                    ->label('Vị trí')
                                    ->numeric()
                                    ->minValue(1)
                                    ->placeholder('VD: 1, 2, 3...')
                                    ->default(function () {
                                        return \App\Models\Menus::max('position') + 1;
                                    })
                                    ->helperText('Giá trị nhỏ hơn sẽ hiển thị trước'),
                            ]),

                        // Tab 2: SEO
                        Forms\Components\Tabs\Tab::make('Thông tin SEO')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->label('Meta Title'),

                                        Forms\Components\TextInput::make('meta_keyword')
                                            ->label('Meta Keyword'),
                                    ]),

                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->autosize(),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tên menu
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên menu')
                    ->searchable()
                    ->sortable(),

                // menu cha
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('menu cha')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                // Trạng thái (Hiển thị / Ẩn)
                Tables\Columns\BadgeColumn::make('is_active') // hoặc 'status'
                ->label('Trạng thái')
                    ->formatStateUsing(fn ($state) => $state ? 'Hoạt động' : 'Không hoạt động'),
                Tables\Columns\BadgeColumn::make('type') // Loại menu
                ->label('Loại menu')
                    ->formatStateUsing(fn ($state) => $state ? 'Nội dung' : 'Liên kết'),
                Tables\Columns\TextColumn::make('position')
                    ->label('Vị trí')
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        $parentName = $record->parent ? $record->parent->name : 'Menu gốc';
                        return "{$parentName} - Vị trí: {$record->position}";
                    })
                    ->description(fn ($record) => $record->parent ? "Thuộc: {$record->parent->name}" : 'Menu gốc')
                    ->limit(25)
                    ->tooltip(function ($record) {
                        $parentName = $record->parent ? $record->parent->name : 'Menu gốc';
                        return "{$parentName} - Vị trí: {$record->position}";
                    }),
            ])
            ->defaultSort('position')
            ->filters([
                Tables\Filters\SelectFilter::make('id_parent')
                    ->label('Menu cha')
                    ->options(\App\Models\Menus::groupedCategories())
                    ->searchable()
                    ->preload()
                    ->placeholder('Chọn menu cha để lọc')
                    ->query(function (Builder $query, array $data): Builder {
                        if (! $data['value']) {
                            return $query;
                        }
                        
                        // Chỉ hiển thị các menu con, không hiển thị menu cha
                        return $query->where('id_parent', $data['value']);
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['value']) {
                            return null;
                        }
                        
                        $parent = \App\Models\Menus::find($data['value']);
                        return $parent ? "Menu cha: {$parent->name}" : null;
                    }),

                Tables\Filters\SelectFilter::make('type')
                    ->label('Loại menu')
                    ->options([
                        0 => 'Liên kết',
                        1 => 'Nội dung',
                    ])
                    ->placeholder('Chọn loại menu để lọc')
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['value']) {
                            return null;
                        }
                        
                        return $data['value'] == 1 ? 'Loại: Nội dung' : 'Loại: Liên kết';
                    }),

                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Trạng thái')
                    ->options([
                        1 => 'Hoạt động',
                        0 => 'Không hoạt động',
                    ])
                    ->placeholder('Chọn trạng thái để lọc')
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['value']) {
                            return null;
                        }
                        
                        return $data['value'] == 1 ? 'Trạng thái: Hoạt động' : 'Trạng thái: Không hoạt động';
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->tooltip('Xem chi tiết')
                    ->iconButton()
                    ->modalHeading('Thông tin menu')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Đóng')
                    ->infolist([
                        Grid::make(2)->schema([
                            TextEntry::make('name')
                                ->label('Tên menu')
                                ->inlineLabel(),

                            TextEntry::make('parent.name')
                                ->label('menu cha')
                                ->placeholder('—')
                                ->inlineLabel(),

                            TextEntry::make('type')
                                ->label('Loại menu')
                                ->inlineLabel()
                                ->formatStateUsing(fn ($state) => $state ? 'Nội dung' : 'Liên kết'),

                            TextEntry::make('createdBy.name')
                                ->label('Người tạo')
                                ->placeholder('—')
                                ->inlineLabel(),

                            TextEntry::make('updatedBy.name')
                                ->label('Người sửa')
                                ->placeholder('—')
                                ->inlineLabel(),

                            TextEntry::make('created_at')
                                ->label('Ngày tạo')
                                ->dateTime('d/m/Y H:i')
                                ->inlineLabel(),

                            TextEntry::make('updated_at')
                                ->label('Ngày sửa')
                                ->dateTime('d/m/Y H:i')
                                ->inlineLabel(),

                            TextEntry::make('is_active')
                                ->label('Trạng thái')
                                ->inlineLabel()
                                ->formatStateUsing(fn ($state) => $state ? 'Hoạt động' : 'Không hoạt động'),

                            TextEntry::make('notes')
                                ->label('Mô tả')
                                ->inlineLabel()
                                ->columnSpanFull(),

                            ImageEntry::make('icon')
                                ->label('Hình ảnh')
                                ->columnSpanFull()
                                ->hidden(fn ($record) => !$record->icon),
                        ]),
                    ]),
                Tables\Actions\EditAction::make()->iconButton()->tooltip('chỉnh sửa'),

                // Action thay đổi vị trí menu
                Tables\Actions\Action::make('moveUp')
                    ->label('Lên trên')
                    ->icon('heroicon-o-arrow-up')
                    ->iconButton()
                    ->tooltip('Di chuyển lên trên')
                    ->color('success')
                    ->visible(function ($record) {
                        $observer = app(\App\Observers\MenusObserver::class);
                        return $observer->canMoveUp($record);
                    })
                    ->action(function ($record) {
                        $observer = app(\App\Observers\MenusObserver::class);
                        $observer->moveUp($record);
                        Notification::make()
                            ->title('Thành công')
                            ->body('Đã di chuyển menu lên trên')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('moveDown')
                    ->label('Xuống dưới')
                    ->icon('heroicon-o-arrow-down')
                    ->iconButton()
                    ->tooltip('Di chuyển xuống dưới')
                    ->color('warning')
                    ->visible(function ($record) {
                        $observer = app(\App\Observers\MenusObserver::class);
                        return $observer->canMoveDown($record);
                    })
                    ->action(function ($record) {
                        $observer = app(\App\Observers\MenusObserver::class);
                        $observer->moveDown($record);
                        Notification::make()
                            ->title('Thành công')
                            ->body('Đã di chuyển menu xuống dưới')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make()
                    ->iconButton()->tooltip('xóa')
                    ->before(function ($record, $action) {
                        if (!$record->canBeDeleted()) {
                            Notification::make()
                                ->title('Không thể xoá Menu')
                                ->body('Menu này có bài viết hoặc menu con. Vui lòng xóa chúng trước.')
                                ->danger()
                                ->send();

                            $action->cancel(); // hủy xoá
                        }
                    })->successNotificationTitle('Đã xóa Menu thành công'),
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
            'index' => Pages\ListMenuses::route('/'),
            'create' => Pages\CreateMenus::route('/create'),
            'edit' => Pages\EditMenus::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }
}
