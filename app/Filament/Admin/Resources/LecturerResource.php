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
                                    ->markAsRequired()
                                    ->maxLength(255)
                                    ->minLength(2)
                                    ->placeholder('Nhập tên giảng viên')
                                    ->rules([
                                        'required',
                                        'min:2',
                                        'max:255'
                                    ])
                                    ->validationMessages([
                                        'required' => 'Tên giảng viên là bắt buộc',
                                        'min' => 'Tên giảng viên phải có ít nhất 2 ký tự',
                                        'max' => 'Tên giảng viên không được quá 255 ký tự',
                                    ])
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->markAsRequired()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('example@email.com')
                                    ->rules([
                                        'required',
                                        'email',
                                        'unique:lecturers,email',
                                        'max:255'
                                    ])
                                    ->validationMessages([
                                        'required' => 'Email là bắt buộc',
                                        'email' => 'Email phải có định dạng hợp lệ',
                                        'unique' => 'Email này đã được sử dụng',
                                        'max' => 'Email không được quá 255 ký tự',
                                    ])
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->label('Số điện thoại')
                                    ->tel()
                                    ->markAsRequired()
                                    ->maxLength(20)
                                    ->minLength(10)
                                    ->placeholder('0123456789')
                                    ->rules([
                                        'required',
                                        'min:10',
                                        'max:20'
                                    ])
                                    ->validationMessages([
                                        'required' => 'Số điện thoại là bắt buộc',
                                        'min' => 'Số điện thoại phải có ít nhất 10 ký tự',
                                        'max' => 'Số điện thoại không được quá 20 ký tự',
                                    ])
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('position')
                                    ->label('Chức vụ')
                                    ->markAsRequired()
                                    ->maxLength(255)
                                    ->minLength(3)
                                    ->placeholder('Giảng viên, Trưởng bộ môn, ...')
                                    ->rules([
                                        'required',
                                        'min:3',
                                        'max:255'
                                    ])
                                    ->validationMessages([
                                        'required' => 'Chức vụ là bắt buộc',
                                        'min' => 'Chức vụ phải có ít nhất 3 ký tự',
                                        'max' => 'Chức vụ không được quá 255 ký tự',
                                    ])
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Phân loại giảng viên')
                                    ->markAsRequired()
                                    ->options(\App\Models\Lecturer::getTypeOptions())
                                    ->placeholder('Chọn phân loại')
                                    ->rules(['required'])
                                    ->validationMessages([
                                        'required' => 'Phân loại giảng viên là bắt buộc',
                                    ])
                                    ->columnSpan(1),

                                Forms\Components\Select::make('faculty_institute')
                                    ->label('Thuộc Khoa/Viện/Phòng ban')
                                    ->markAsRequired()
                                    ->options(\App\Helpers\MenuHelper::getFacultyInstituteOptions())
                                    ->searchable()
                                    ->placeholder('Chọn khoa/viện/phòng ban')
                                    ->rules(['required'])
                                    ->validationMessages([
                                        'required' => 'Khoa/Viện/Phòng ban là bắt buộc',
                                    ])
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('academic_degree')
                                    ->label('Học vị')
                                    ->markAsRequired()
                                    ->options(\App\Models\Lecturer::getAcademicDegreeOptions())
                                    ->placeholder('Chọn học vị')
                                    ->rules(['required'])
                                    ->validationMessages([
                                        'required' => 'Học vị là bắt buộc',
                                    ])
                                    ->columnSpan(1),

                                Forms\Components\Select::make('academic_title')
                                    ->label('Học hàm')
                                    ->markAsRequired()
                                    ->options(\App\Models\Lecturer::getAcademicTitleOptions())
                                    ->placeholder('Chọn học hàm')
                                    ->rules(['required'])
                                    ->validationMessages([
                                        'required' => 'Học hàm là bắt buộc',
                                    ])
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('official_title')
                                    ->label('Chức danh quản lý')
                                    ->maxLength(550)
                                    ->minLength(3)
                                    ->placeholder('Trưởng khoa, Phó hiệu trưởng, ...')

                                    ->columnSpan(1),

                                Forms\Components\Select::make('major_id')
                                    ->label('Chuyên ngành đào tạo')
                                    ->markAsRequired()
                                    ->relationship('major', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Chọn chuyên ngành')
                                    ->rules(['required'])
                                    ->validationMessages([
                                        'required' => 'Chuyên ngành đào tạo là bắt buộc',
                                    ])
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Hình ảnh & Mạng xã hội')
                    ->description('Ảnh đại diện và thông tin liên lạc')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\FileUpload::make('avatar')
                            ->label('Ảnh đại diện')
                            ->image()
                            ->imageEditor()
                                    ->imageCropAspectRatio('1:1')
                                    ->imageResizeTargetWidth('400')
                                    ->imageResizeTargetHeight('400')
                                    ->placeholder('Chọn hoặc kéo thả ảnh')
                                    ->columnSpan(1),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('facebook')
                                            ->label('Facebook')
                                            ->maxLength(550)
                                            ->url()
                                            ->regex('/^https?:\/\/(www\.)?facebook\.com\/.+$/')
                                            ->placeholder('https://facebook.com/...')

                                            ->columnSpan(1),

                                        Forms\Components\TextInput::make('zalo')
                                            ->label('Zalo')
                                            ->maxLength(550)
                                            ->regex('/^[0-9+\-\s()]+$/')
                                            ->placeholder('Số điện thoại Zalo')

                                            ->columnSpan(1),
                                    ])
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Mô tả & Nội dung')
                    ->description('Thông tin chi tiết về giảng viên')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->label('Giới thiệu ngắn')
                            ->markAsRequired()
                            ->minLength(10)
                            ->maxLength(2000)
                            ->placeholder('Nhập giới thiệu ngắn về giảng viên')
                            ->rules([
                                'required',
                                'min:10',
                                'max:2000'
                            ])
                            ->validationMessages([
                                'required' => 'Giới thiệu ngắn là bắt buộc',
                                'min' => 'Giới thiệu ngắn phải có ít nhất 10 ký tự',
                                'max' => 'Giới thiệu ngắn không được quá 2000 ký tự',
                            ])
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'link',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Cài đặt & Trạng thái')
                    ->description('Cài đặt hiển thị và thông tin hệ thống')
                    ->icon('heroicon-o-cog')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
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

                                    Forms\Components\Toggle::make('is_research')
                                    ->label('Tham gia nghiên cứu khoa học')
                                    ->inline()->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('position_order')
                                    ->label('Thứ tự sắp xếp')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(999999)
                                    ->default(0)
                                    ->placeholder('Tự động tăng')
                                    ->helperText('Để trống để tự động tăng')
                                    ->rules([
                                        'numeric',
                                        'min:0',
                                        'max:999999'
                                    ])
                                    ->validationMessages([
                                        'numeric' => 'Thứ tự sắp xếp phải là số',
                                        'min' => 'Thứ tự sắp xếp phải từ 0 trở lên',
                                        'max' => 'Thứ tự sắp xếp không được quá 999999',
                                    ])
                                    ->columnSpan(1),

                                
                            ]),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Ảnh đại diện')
                    ->square()
                    ->size(50)
                    ->visible(fn ($record) => $record && $record->avatar !== null),

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
                    ->copyable()
                    ->icon('heroicon-m-phone'),

                Tables\Columns\TextColumn::make('faculty_institute_full_name')
                    ->label('Khoa/Viện')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('major.name')
                    ->label('Chuyên ngành')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('position_order')
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
            ->defaultSort('position_order')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Phân loại giảng viên')
                    ->options(\App\Models\Lecturer::getTypeOptions()),

                Tables\Filters\SelectFilter::make('faculty_institute')
                    ->label('Khoa/Viện/Phòng ban')
                    ->options(\App\Helpers\MenuHelper::getFacultyInstituteOptions())->searchable(),

                Tables\Filters\SelectFilter::make('academic_degree')
                    ->label('Học vị')
                    ->options(\App\Models\Lecturer::getAcademicDegreeOptions()),

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
                    ->tooltip('Sửa giảng viên')
                    ->icon('heroicon-o-pencil-square')
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->tooltip('Xóa giảng viên')
                    ->icon('heroicon-o-trash')
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->tooltip('Xóa giảng viên')
                    ->icon('heroicon-o-trash')
                    ->iconButton(),
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

