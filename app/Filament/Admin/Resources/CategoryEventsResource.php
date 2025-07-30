<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CategoryEventsResource\Pages;
use App\Filament\Admin\Resources\CategoryEventsResource\RelationManagers;
use App\Models\CategoryEvents;
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

class CategoryEventsResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;

    protected static ?string $model = CategoryEvents::class;

    protected static ?string $modelLabel = 'Danh mục sự kiện';
    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $activeNavigationIcon = 'heroicon-s-folder';
    protected static ?string $navigationGroup = 'Quản lý sự kiện';
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
                Forms\Components\Tabs::make('Tạo/Cập nhật Danh mục ')
                    ->columnSpanFull()
                    ->tabs([
                        // Tab 1: Thông tin chính
                        Forms\Components\Tabs\Tab::make('Thông tin chính')
                            ->schema([
                                Forms\Components\Grid::make(1)
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Tên Danh mục ')
                                            ->placeholder('Nhập tên Danh mục ')
                                            ->markAsRequired()
                                            ->rules([
                                                'required',
                                                'max:255',
                                                fn($record) => $record
                                                    ? "unique:category_events,name,{$record->id}"
                                                    : 'unique:category_events,name',
                                            ])
                                            ->validationMessages([
                                                'required' => 'Tên Danh mục  không được để trống.',
                                                'max' => 'Tên Danh mục  không được vượt quá :max ký tự.',
                                                'unique' => 'Tên Danh mục  đã tồn tại.',
                                            ]),
                                    ]),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('type')
                                            ->label('Loại Danh mục ')
                                            ->options([
                                                0 => 'Liên kết',
                                                1 => 'Nội dung',
                                            ])
                                            ->required()
                                            ->validationMessages([
                                                'required' => 'loại Danh mục  không được để trống.',
                                            ])
                                            ->native(false)
                                            ->placeholder('Chọn loại Danh mục '),

                                        Forms\Components\Select::make('id_parent')
                                            ->label('Danh mục cha')
                                            ->options(\App\Models\CategoryEvents::groupedCategories())
                                            ->searchable()
                                            ->preload()
                                            ->placeholder('Chọn Danh mục  cha'),
                                    ]),

                                Forms\Components\FileUpload::make('icon')
                                    ->label('Icon')
                                    ->image()
                                    ->disk('public')
                                    ->directory('categoryEvents/images')
                                    ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/jpeg', 'image/webp'])
                                    ->imageEditor(),

                                Forms\Components\Textarea::make('notes')
                                    ->label('Ghi chú')
                                    ->placeholder('Nhập ghi chú'),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Kích hoạt')
                                    ->default(true),
                                Forms\Components\TextInput::make('position')
                                    ->label('Vị trí')
                                    ->numeric()
                                    ->minValue(1)
                                    ->placeholder('VD: 1, 2, 3...')
                                    ->default(function () {
                                        return \App\Models\CategoryEvents::max('position') + 1;
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
                // Tên Danh mục
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên Danh mục ')
                    ->searchable()
                    ->sortable(),

                // Danh mục  cha
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Danh mục  cha')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                // Người tạo
                Tables\Columns\TextColumn::make('createdBy.name') // assuming quan hệ với User
                ->label('Người tạo')
                    ->sortable()
                    ->placeholder('—'),

                // Người sửa
                Tables\Columns\TextColumn::make('updatedBy.name') // assuming quan hệ với User
                ->label('Người sửa')
                    ->sortable()
                    ->placeholder('—'),

                // Thời gian tạo
                Tables\Columns\TextColumn::make('created_date')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                // Thời gian sửa
                Tables\Columns\TextColumn::make('updated_date')
                    ->label('Ngày sửa')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                // Trạng thái (Hiển thị / Ẩn)
                Tables\Columns\BadgeColumn::make('is_active') // hoặc 'status'
                ->label('Trạng thái')
                    ->formatStateUsing(fn ($state) => $state ? 'Hoạt động' : 'Không hoạt động'),
                Tables\Columns\BadgeColumn::make('type') // Loại Danh mục
                ->label('Loại Danh mục ')
                    ->formatStateUsing(fn ($state) => $state ? 'Nội dung' : 'Liên kết'),
                Tables\Columns\TextColumn::make('position')
                    ->label('Vị trí')
                    ->sortable(),
            ])
            ->defaultSort('position')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->tooltip('Xem chi tiết')
                    ->iconButton()
                    ->modalHeading('Thông tin Danh mục ')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Đóng')
                    ->infolist([
                        Grid::make(2)->schema([
                            TextEntry::make('name')
                                ->label('Tên Danh mục ')
                                ->inlineLabel(),

                            TextEntry::make('parent.name')
                                ->label('Danh mục  cha')
                                ->placeholder('—')
                                ->inlineLabel(),

                            TextEntry::make('type')
                                ->label('Loại Danh mục ')
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
                    })->successNotificationTitle('Đã xóa Menu thành công')
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
            'index' => Pages\ListCategoryEvents::route('/'),
            'create' => Pages\CreateCategoryEvents::route('/create'),
            'edit' => Pages\EditCategoryEvents::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }
}
