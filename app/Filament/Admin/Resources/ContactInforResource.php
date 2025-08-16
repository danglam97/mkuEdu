<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ContactInforResource\Pages;
use App\Models\ContactInfor;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactInforResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;
    
    protected static ?string $model = ContactInfor::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationLabel = 'Thông tin liên hệ';
    protected static ?string $modelLabel = 'Thông tin liên hệ';
    protected static ?string $pluralModelLabel = 'Thông tin liên hệ';
    protected static ?string $navigationGroup = 'Quản lý nội dung';

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
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->description('Thông tin người liên hệ')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Tên người liên hệ')
                                    ->required()
                                    ->maxLength(500)
                                    ->placeholder('Nhập tên người cần liên hệ')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('type')
                                    ->label('Loại liên hệ')
                                    ->maxLength(550)
                                    ->placeholder('Giảng viên, Nhân viên, Sinh viên...')
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Textarea::make('addresss')
                            ->label('Địa chỉ liên hệ')
                            ->placeholder('Nhập địa chỉ liên hệ')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Hình ảnh & Liên kết')
                    ->description('Ảnh đại diện và thông tin liên lạc')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label('Ảnh đại diện')
                                    ->image()
                                    ->imageEditor()
                                    ->imageCropAspectRatio('1:1')
                                    ->imageResizeTargetWidth('400')
                                    ->imageResizeTargetHeight('400')
                                    ->placeholder('Chọn ảnh đại diện')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('link_url')
                                    ->label('Link website')
                                    ->url()
                                    ->maxLength(550)
                                    ->placeholder('https://example.com')
                                    ->helperText('Địa chỉ website nếu có (link khoa / viện / ...)')
                                    ->columnSpan(1),
                            ]),
                    ]),

                Forms\Components\Section::make('Thông tin liên lạc')
                    ->description('Email, điện thoại và mạng xã hội')
                    ->icon('heroicon-o-envelope')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(500)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('contact@example.com')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('phone')
                                    ->label('Số điện thoại')
                                    ->tel()
                                    ->maxLength(500)
                                    ->placeholder('0123456789')
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('facebook')
                                    ->label('Facebook')
                                    ->url()
                                    ->maxLength(550)
                                    ->placeholder('https://facebook.com/...')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('zalo')
                                    ->label('Zalo')
                                    ->maxLength(550)
                                    ->placeholder('https://zalo.me/...')
                                    ->columnSpan(1),
                            ]),
                    ]),

                Forms\Components\Section::make('Phân loại & Hiển thị')
                    ->description('Thuộc chuyên ngành và cài đặt hiển thị')
                    ->icon('heroicon-o-tag')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('major_id')
                                    ->label('Chuyên ngành')
                                    ->relationship('major', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Chọn chuyên ngành')
                                    ->columnSpan(1),

                                Forms\Components\Select::make('faculty_institute')
                                    ->label('Khoa/Viện/Phòng ban')
                                    ->options(\App\Helpers\MenuHelper::getFacultyInstituteOptions())
                                    ->searchable()
                                    ->placeholder('Chọn khoa/viện/phòng ban')
                                    ->helperText('Lấy ID từ menus (với slug: cac-phong-ban; cac-khoa; cac-vien; cac-trung-tam)')
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Toggle::make('is_home')
                                    ->label('Hiển thị trang chủ')
                                    ->default(false)
                                    ->onColor('success')
                                    ->offColor('gray')
                                    ->onIcon('heroicon-s-home')
                                    ->offIcon('heroicon-s-home')
                                    ->columnSpan(1),

                                Forms\Components\Toggle::make('isactive')
                                    ->label('Trạng thái hiển thị')
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->onIcon('heroicon-s-check')
                                    ->offIcon('heroicon-s-x-mark')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('position')
                                    ->label('Thứ tự sắp xếp')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(999)
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
                Tables\Columns\ImageColumn::make('image')
                    ->label('Ảnh')
                    ->square()
                    ->size(50)
                    ->visible(fn ($record) => $record && $record->image !== null),

                Tables\Columns\TextColumn::make('name')
                    ->label('Tên')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Loại')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('faculty_institute_name')
                    ->label('Khoa/Viện')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('major_name')
                    ->label('Chuyên ngành')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Điện thoại')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone'),

                Tables\Columns\TextColumn::make('position')
                    ->label('Thứ tự')
                    ->sortable()
                    ->numeric(),

                Tables\Columns\BadgeColumn::make('isactive')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn ($state) => $state ? 'Hoạt động' : 'Không hoạt động')
                    ->colors([
                        'success' => 'Hoạt động',
                        'danger' => 'Không hoạt động',
                    ])
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('is_home')
                    ->label('Trang chủ')
                    ->formatStateUsing(fn ($state) => $state ? 'Có' : 'Không')
                    ->colors([
                        'success' => 'Có',
                        'gray' => 'Không',
                    ])
                    ->sortable(),
            ])
            ->defaultSort('position')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Loại liên hệ')
                    ->options([
                        'giang_vien' => 'Giảng viên',
                        'nhan_vien' => 'Nhân viên',
                        'sinh_vien' => 'Sinh viên',
                        'khac' => 'Khác',
                    ]),

                Tables\Filters\SelectFilter::make('faculty_institute')
                    ->label('Khoa/Viện/Phòng ban')
                    ->options(\App\Helpers\MenuHelper::getFacultyInstituteOptions())
                    ->searchable(),

                Tables\Filters\SelectFilter::make('major_id')
                    ->label('Chuyên ngành')
                    ->relationship('major', 'name')
                    ->searchable()
                    ->preload(),

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
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->tooltip('Xem')
                    ->iconButton()
                    ->icon('heroicon-s-eye')
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->tooltip('Sửa')
                    ->icon('heroicon-o-pencil-square')
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->tooltip('Xóa')
                    ->icon('heroicon-o-trash')
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->tooltip('Xóa đã chọn')
                    ->icon('heroicon-o-trash'),
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
            'index' => Pages\ListContactInfors::route('/'),
            'create' => Pages\CreateContactInfor::route('/create'),
            'view' => Pages\ViewContactInfor::route('/{record}'),
            'edit' => Pages\EditContactInfor::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? static::getModel()::count()
            : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
