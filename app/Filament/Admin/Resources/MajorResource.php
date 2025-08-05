<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MajorResource\Pages;
use App\Filament\Admin\Resources\MajorResource\RelationManagers;
use App\Models\Major;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $activeNavigationIcon = 'heroicon-o-rectangle-stack';
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
                    Forms\Components\Grid::make(1)->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên ngành học')
                            ->placeholder('Nhập tên ngành học')
                            ->required()->maxLength(255)->validationMessages([
                                'required' => 'Tên ngành học là bắt buộc',
                                'max' => 'Tên ngành học không được vượt quá 255 ký tự',
                            ]),
                        Forms\Components\TextInput::make('code')
                            ->label('Mã ngành học')
                            ->placeholder('Nhập mã ngành học')
                            ->hidden(),
                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->placeholder('Nhập mô tả'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Trạng thái')
                            ->default(true),
                        Forms\Components\FileUpload::make('icon')
                            ->label('Ảnh đại diện')
                            ->image(),
                            // ->required()->validationMessages([
                            //     'required' => 'Ảnh đại diện là bắt buộc',
                            // ]),
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
                Tables\Columns\BadgeColumn::make('is_active')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn ($state) => $state ? 'Hoạt động' : 'Không hoạt động')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('icon')
                    ->label('Ảnh đại diện')
                    ->sortable()
                    ->searchable(),
            ])->defaultSort('name')
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Trạng thái')
                    ->options([
                        '1' => 'Hoạt động',
                        '0' => 'Không hoạt động',
                    ]),
            ])
            ->actions([
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
}
