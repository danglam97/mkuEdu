<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MajorResource\Pages;
use App\Filament\Admin\Resources\MajorResource\RelationManagers;
use App\Models\Major;
use App\Models\Menus;
use App\Helpers\MenuHelper;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;

class MajorResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;
    protected static ?string $model = Major::class;

    protected static ?string $modelLabel = 'Ngành học'; // customize ten cua model

    protected static bool $hasTitleCaseModelLabel = false; // khong viet hoa chu cai dau tien trong ten cua model

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?string $activeNavigationIcon = 'heroicon-o-light-bulb';
    protected static ?string $navigationGroup = 'Quản lý ngành học';
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
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên ngành học')
                            ->placeholder('Nhập tên ngành học')
                            ->required()->maxLength(255)->validationMessages([
                                'required' => 'Tên ngành học là bắt buộc',
                                'max' => 'Tên ngành học không được vượt quá 255 ký tự',
                            ])->columnSpan(1),
                        Forms\Components\TextInput::make('code')
                            ->label('Mã ngành học')
                            ->placeholder('Nhập mã ngành học')
                            ->unique(ignoreRecord: true)
                            ->columnSpan(1),
                        Forms\Components\Select::make('faculty_institute')
                            ->label('Thuộc Khoa/Viện/Phòng ban')
                            ->options(MenuHelper::getFacultyInstituteOptions())
                            ->searchable()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('link_url')
                            ->label('Link URL')
                            ->placeholder('Nhập link URL')
                            ->url()
                            ->columnSpan(1),
                        Forms\Components\FileUpload::make('image')
                            ->label('Hình ảnh đại diện')
                            ->image()
                            ->columnSpan(2),
                        Forms\Components\RichEditor::make('description')
                            ->label('Mô tả ngắn')
                            ->placeholder('Nhập mô tả ngắn')->toolbarButtons([
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
                                    ])->columnSpan(2),
                        Forms\Components\RichEditor::make('contents')
                            ->label('Nội dung chi tiết')
                            ->placeholder('Nhập nội dung chi tiết')->toolbarButtons([
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
                                    ])->columnSpan(2),
                    ]),
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\Toggle::make('isactive')
                            ->label('Trạng thái hiển thị')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->onIcon('heroicon-s-check')
                            ->offIcon('heroicon-s-x-mark')
                            ->columnSpan(1),
                        Forms\Components\Toggle::make('is_home')
                            ->label('Hiển thị trang chủ')
                            ->default(false)
                            ->onColor('success')
                            ->offColor('gray')
                            ->onIcon('heroicon-s-home')
                            ->offIcon('heroicon-s-home')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('position')
                            ->label('Thứ tự hiển thị')
                            ->numeric()
                            ->default(0)
                            ->placeholder('Tự động tăng')
                            ->helperText('Để trống để tự động tăng')
                            ->columnSpan(1),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên ngành học')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Mã ngành')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Loại ngành')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('faculty_institute_full_name')
                    ->label('Khoa/Viện')
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->label('Thứ tự')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('isactive')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn ($state) => $state ? 'Hoạt động' : 'Không hoạt động')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('is_home')
                    ->label('Trang chủ')
                    ->formatStateUsing(fn ($state) => $state ? 'Có' : 'Không')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Hình ảnh')
                    ->circular(),
            ])->defaultSort('position')
            ->filters([
                Tables\Filters\SelectFilter::make('isactive')
                    ->label('Trạng thái')
                    ->options([
                        '1' => 'Hoạt động',
                        '0' => 'Không hoạt động',
                    ]),
                Tables\Filters\SelectFilter::make('is_home')
                    ->label('Hiển thị trang chủ')
                    ->options([
                        '1' => 'Có',
                        '0' => 'Không',
                    ]),
                Tables\Filters\SelectFilter::make('faculty_institute')
                    ->label('Khoa/Viện')
                    ->options(MenuHelper::getFacultyInstituteOptions()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->tooltip('Xem')
                    ->iconButton()
                    ->icon('heroicon-s-eye')
                    ->color('info'),
                Tables\Actions\EditAction::make()->tooltip('Sửa ngành học')->icon('heroicon-o-pencil-square')->iconButton(),
                Tables\Actions\DeleteAction::make()->tooltip('Xóa ngành học')->icon('heroicon-o-trash')->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->tooltip('Xóa ngành học')->icon('heroicon-o-trash')->iconButton(),
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
            'index' => Pages\ListMajors::route('/'),
            'create' => Pages\CreateMajor::route('/create'),
            'edit' => Pages\EditMajor::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-academic-cap';
    }
}
