<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DepartmentResource\Pages;
use App\Filament\Admin\Resources\DepartmentResource\RelationManagers;
use App\Models\Department;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;
    protected static ?string $model = Department::class;

    protected static ?string $modelLabel = 'Phòng ban'; // customize ten cua model

    protected static bool $hasTitleCaseModelLabel = false; // khong viet hoa chu cai dau tien trong ten cua model

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $activeNavigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Quản lý người dùng';
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
                Forms\Components\Section::make('Thông tin phòng ban') // tiêu đề nền
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Tên phòng ban')
                                    ->required()
                                    ->maxLength(255)
                                    ->rules(['required', 'max:255'])
                                    ->placeholder('Nhập tên phòng ban')
                                    ->validationMessages([
                                        'required' => 'Vui lòng nhập tên phòng ban.',
                                        'max' => 'Tên phòng ban không được vượt quá :max ký tự.',
                                    ]),

                                Forms\Components\TextInput::make('code')
                                    ->label('Mã phòng ban')
                                    ->maxLength(255)
                                    ->rules(['nullable', 'max:255'])
                                    ->placeholder('VD: PB001, KD2023')
                                    ->validationMessages([
                                        'max' => 'Mã phòng ban không được vượt quá :max ký tự.',
                                    ]),

                                Forms\Components\Select::make('parent_id')
                                    ->label('Phòng ban cha')
                                    ->options(fn () =>self:: getDepartmentOptions())

                                    ->searchable()
                                    ->placeholder('— Phòng ban cha —')
                                    ->rules(['nullable', 'exists:departments,id'])
                                    ->validationMessages([
                                        'exists' => 'Phòng ban cha không hợp lệ.',
                                    ])
                                    ->columnSpan(2),

                                Forms\Components\Textarea::make('description')
                                    ->label('Mô tả')
                                    ->rows(3)
                                    ->maxLength(1000)
                                    ->placeholder('Mô tả thêm về phòng ban (nếu có)')
                                    ->rules(['nullable', 'max:1000'])
                                    ->validationMessages([
                                        'max' => 'Mô tả không được vượt quá :max ký tự.',
                                    ])
                                    ->columnSpan(2),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Hoạt động')
                                    ->inline(false)
                                    ->default(true)
                                    ->columnSpan(2),
                            ]),
                    ])
                    ->columns(1) // full chiều ngang
                    ->collapsible() // tùy chọn: có thể thu gọn
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên phòng ban')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Mã phòng ban')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Phòng ban cha')
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Mô tả')
                    ->limit(50)
                    ->wrap(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Hoạt động')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->tooltip('sửa')->iconButton(),
                Tables\Actions\DeleteAction::make()->tooltip('Xóa')->iconButton()->before(function ($record, $action) {
                    if (!$record->canBeDeleted()) {
                        Notification::make()
                            ->title('Không thể xoá Phòng ban')
                            ->body('Phòng ban này vẫn còn phòng ban con và người dùng. Vui lòng xoá phòng ban con và người dùng trước.')
                            ->danger()
                            ->send();

                        $action->cancel(); // hủy xoá
                    }
                })->successNotificationTitle('Đã xóa phòng ban thành công'),
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
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }
    public static  function getDepartmentOptions($parentId = null, $prefix = ''): array
    {
        $options = [];

        Department::where('parent_id', $parentId)
            ->orderBy('name')
            ->get()
            ->each(function ($dept) use (&$options, $prefix) {
                $options[$dept->id] = $prefix . $dept->name;
                $options += self:: getDepartmentOptions($dept->id, $prefix . '— ');
            });

        return $options;
    }
}
