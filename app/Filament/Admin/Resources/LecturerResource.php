<?php

namespace App\Filament\Admin\Resources;

use App\Enums\Post\PostIsActive;
use App\Filament\Admin\Resources\LecturerResource\Pages;
use App\Filament\Admin\Resources\LecturerResource\RelationManagers;
use App\Models\Lecturer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\GlobalSearch\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use App\Forms\Components\CKEditor;

class LecturerResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;

    protected static ?string $model = Lecturer::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Quản lý ngành học';

    protected static ?string $modelLabel = 'Giảng viên';
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
                Forms\Components\Section::make('Thông tin cá nhân')
                    ->description('Nhập thông tin cơ bản của giảng viên')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Tên giảng viên')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Nhập tên giảng viên')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('example@email.com')
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->label('Số điện thoại')
                                    ->tel()
                                    ->maxLength(20)
                                    ->placeholder('0123456789')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('position')
                                    ->label('Chức vụ')
                                    ->maxLength(255)
                                    ->placeholder('Giảng viên, Trưởng bộ môn, ...')
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('isactive')
                                    ->label('Trạng thái hiển thị')
                                    ->inline(),
                            ]),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Chuyên ngành & Mô tả')
                    ->description('Thông tin về chuyên môn và giới thiệu')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\Select::make('major_id')
                                    ->label('Chuyên ngành đào tạo')
                                    ->relationship('major', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Chọn chuyên ngành')
                                    ->required()
                                    ->columnSpan(1),

                                CKEditor::make('description')
                                    ->label('Giới thiệu ngắn')
                                    ->placeholder('Mô tả ngắn về giảng viên, chuyên môn, kinh nghiệm...')
                            ]),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Ảnh đại diện')
                    ->description('Upload ảnh đại diện cho giảng viên')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Forms\Components\FileUpload::make('avatar')
                            ->label('Ảnh đại diện')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('lecturers/image')
                            ->helperText('Kích thước tối đa: 2MB. Định dạng: JPG, PNG, GIF. Tỷ lệ khung hình: 1:1')
                    ])
                    ->columns(1)
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Ảnh đại diện')
                    ->defaultImageUrl(asset('images/default-avatar.png')) // ảnh mặc định
                    ->square()
                    ->size(50),

                Tables\Columns\TextColumn::make('name')
                    ->label('Tên giảng viên')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Số điện thoại')
                    ->searchable()
                    ->icon('heroicon-m-phone'),

                Tables\Columns\TextColumn::make('position')
                    ->label('Chức vụ')
                    ->badge()
                    ->color('info'),

                Tables\Columns\BadgeColumn::make('isactive')
                    ->label('Trạng thái hoạt động')
                    ->formatStateUsing(fn($state) => PostIsActive::tryFrom($state)?->getLabel() ?? '')
                    ->sortable(),

                Tables\Columns\TextColumn::make('major.name')
                    ->label('Chuyên ngành')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('major_id')
                    ->label('Lọc theo chuyên ngành')
                    ->relationship('major', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('isactive')
                    ->label('Trạng thái hiển thị')
                    ->options(PostIsActive::options())
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->tooltip('Xem')->iconButton()
                    ->icon('heroicon-m-eye'),
                Tables\Actions\EditAction::make()->tooltip('Sửa')->iconButton()
                    ->icon('heroicon-m-pencil-square'),
                Tables\Actions\DeleteAction::make()->tooltip('Xóa')->iconButton()
                    ->icon('heroicon-m-trash'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListLecturers::route('/'),
            'create' => Pages\CreateLecturer::route('/create'),
            'edit' => Pages\EditLecturer::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
    public static function getGlobalSearchResultDetails(Model|\Illuminate\Database\Eloquent\Model $record): array
    {
        // Customize hien thi chi tiet ket qua tim kiem toan cuc
        return [
            'Name' => $record->name,
            'Email' => $record->email,
        ];
    }

    public static function getGlobalSearchResultUrl(Model|\Illuminate\Database\Eloquent\Model $record): string
    {
        // link chuyen huong sau khi bam vao ban ghi
        return self::getUrl('edit', ['record' => $record]);
    }

    public static function getGlobalSearchResultActions(Model|\Illuminate\Database\Eloquent\Model $record): array
    {
        // cac hanh dong hien thi trong ket qua tim kiem toan cuc
        return [
            Action::make('edit')
                ->label('Sửa')
                ->button()
                ->url(static::getUrl('edit', ['record' => $record]), true),
        ];
    }
}

