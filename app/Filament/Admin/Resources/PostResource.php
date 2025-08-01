<?php

namespace App\Filament\Admin\Resources;

use App\Enums\Post\PostStatus;
use App\Filament\Admin\Resources\PostResource\Pages;
use App\Filament\Admin\Resources\PostResource\RelationManagers;
use App\Forms\Components\CKEditor;
use App\Models\Menus;
use App\Models\Post;
use App\Models\PostApproval;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
class PostResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;

    protected static ?string $model = Post::class;

    protected static ?string $modelLabel = 'Bài viết';
    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $activeNavigationIcon = 'heroicon-o-document';
    protected static ?string $navigationGroup = 'Quản lý menu';
    protected static ?int $navigationSort = 2;
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'approve',
            'refuse',
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tạo/Cập nhật Tin tức')
                    ->columnSpanFull()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Thông tin chính')
                            ->schema([
                                Forms\Components\Grid::make(1)->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Tên tin tức')
                                        ->required()
                                        ->maxLength(500)
                                        ->validationMessages([
                                            'required' => 'Tên tin tức  không được để trống.',
                                            'max' => 'Tên tin tức không được vượt quá :max ký tự.',
                                        ]),

                                    Forms\Components\Textarea::make('description')
                                        ->label('Mô tả ngắn')
                                        ->rows(3),

                                    Forms\Components\FileUpload::make('image')
                                        ->label('Hình ảnh đại diện')
                                        ->image()
                                        ->disk('public')
                                        ->directory('news/images')
                                        ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp']),

                                    Forms\Components\Select::make('id_category')
                                        ->label('Danh mục tin')
                                        ->options(self::getCategoryOptions()) // sửa theo mối quan hệ của bạn
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->validationMessages([
                                            'required' => 'Danh mục tin tưc  không được để trống.',
                                        ]),
                                    CKEditor::make('contents')
                                        ->label('Nội dung tin tức')->required(),
                                    Forms\Components\TextInput::make('link_url')
                                        ->label('Link tập tin')
                                        ->maxLength(550),

                                    Forms\Components\Toggle::make('is_home')
                                        ->label('Hiển thị lên trang chủ')
                                        ->inline(),
                                ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Thông tin SEO')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('meta_title')
                                        ->label('Meta Title')
                                        ->maxLength(100),

                                    Forms\Components\TextInput::make('meta_keyword')
                                        ->label('Meta Keyword')
                                        ->maxLength(500),
                                ]),

                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên tin tức')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Mô tả ngắn')
                    ->limit(50),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Hình ảnh')
                    ->disk('public'), // tùy thuộc cấu hình filesystem

                Tables\Columns\TextColumn::make('category.name') // nếu bạn có quan hệ ->category()
                ->label('Danh mục')
                    ->sortable(),

                Tables\Columns\TextColumn::make('is_home')
                    ->label('Hiển thị lên trang chủ')
                    ->formatStateUsing(fn($state) => $state ? 'Có' : 'Không')
                    ->sortable(),
                Tables\Columns\TextColumn::make('isactive')
                    ->label('trạng thái')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => PostStatus::tryFrom($state)?->getLabel() ?? ''),
            ]) ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('isactive')
                    ->label('Trạng thái')
                    ->options(PostStatus::options())
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->tooltip('Xem chi tiết')
                    ->iconButton()
                    ->modalHeading('Thông tin bài viết')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Đóng')
                    ->infolist([
                        Grid::make(1)->schema([
                            TextEntry::make('name')
                                ->label('Tên bài viết')
                                ->inlineLabel(),

                            TextEntry::make('description')
                                ->label('Mô tả ngắn')
                                ->inlineLabel(),

                            TextEntry::make('category.name')
                                ->label('Danh mục bài viết')
                                ->inlineLabel(),

                            TextEntry::make('link_url')
                                ->label('Link tập tin')
                                ->inlineLabel()->hidden(fn ($record) => !$record->link_url),

                            TextEntry::make('is_home')
                                ->label('Hiển thị lên trang chủ')
                                ->inlineLabel()
                                ->formatStateUsing(fn ($state) => $state ? '✔ Có' : '✘ Không'),
                            TextEntry::make('isactive')
                                ->label('trạng thái')
                                ->inlineLabel()
                                ->formatStateUsing(fn ($state) => PostStatus::tryFrom($state)?->getLabel() ?? ''),

                            TextEntry::make('created_at')
                                ->label('Ngày tạo')
                                ->inlineLabel()
                                ->dateTime('d/m/Y H:i'),

                            TextEntry::make('updated_at')
                                ->label('Ngày sửa')
                                ->inlineLabel()
                                ->dateTime('d/m/Y H:i'),
                        ]),

                        Grid::make()->schema([
                            ImageEntry::make('image')
                                ->label('Hình ảnh đại diện')
                                ->columnSpanFull()
                                ->hidden(fn ($record) => !$record->image),
                        ]),

                        Grid::make()->schema([
                            TextEntry::make('contents')
                                ->label('Nội dung tin tức')
                                ->inlineLabel()
                                ->columnSpanFull()
                                ->extraAttributes([
                                    'style' => 'max-height: 180px; overflow-y: auto; padding: 8px;',
                                ])
                                ->prose()
                                ->html(), // nếu nội dung có thẻ HTML từ CKEditor

                        ]),
                    ]),

                Tables\Actions\EditAction::make()->tooltip('chỉnh sửa')->iconButton(),
                Tables\Actions\DeleteAction::make()->tooltip('xóa')->iconButton() ->successNotificationTitle('Đã xóa bài viết thành công'),
                Tables\Actions\Action::make('reviewAndApprove')
                    ->tooltip('Duyệt bài viết')
                    ->iconButton()
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $record->isactive != 1)
                    ->record(fn($record) => $record)
                    ->modalHeading('Xem chi tiết bài viết')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Đóng')
                    ->infolist([
                        Grid::make(1)->schema([
                            TextEntry::make('name')
                                ->label('Tên bài viết')
                                ->inlineLabel(),

                            TextEntry::make('description')
                                ->label('Mô tả ngắn')
                                ->inlineLabel(),

                            TextEntry::make('category.name')
                                ->label('Danh mục bài viết')
                                ->inlineLabel(),

                            TextEntry::make('link_url')
                                ->label('Link tập tin')
                                ->inlineLabel()->hidden(fn ($record) => !$record->link_url),

                            TextEntry::make('is_home')
                                ->label('Hiển thị lên trang chủ')
                                ->inlineLabel()
                                ->formatStateUsing(fn ($state) => $state ? '✔ Có' : '✘ Không'),
                                TextEntry::make('isactive')
                                ->label('trạng thái')
                                ->inlineLabel()
                                ->formatStateUsing(fn ($state) => PostStatus::tryFrom($state)?->getLabel() ?? ''),

                            TextEntry::make('created_at')
                                ->label('Ngày tạo')
                                ->inlineLabel()
                                ->dateTime('d/m/Y H:i'),

                            TextEntry::make('updated_at')
                                ->label('Ngày sửa')
                                ->inlineLabel()
                                ->dateTime('d/m/Y H:i'),
                        ]),

                        Grid::make()->schema([
                            ImageEntry::make('image')
                                ->label('Hình ảnh đại diện')
                                ->columnSpanFull()
                                ->hidden(fn ($record) => !$record->image),
                        ]),

                        Grid::make()->schema([
                            TextEntry::make('contents')
                                ->label('Nội dung tin tức')
                                ->inlineLabel()
                                ->columnSpanFull()
                                ->extraAttributes([
                                    'style' => 'max-height: 180px; overflow-y: auto; padding: 8px;',
                                ])
                                ->prose()
                                ->html(), // nếu nội dung có thẻ HTML từ CKEditor

                        ]),
                    ])
                    ->modalFooterActions(fn($record) => [
                        Tables\Actions\Action::make('approve')
                            ->label('Duyệt bài viết')
                            ->color('success')
                            ->visible(fn() => $record->isactive != 1)
                            ->action(function ($record, Tables\Actions\Action $action) {
                                $record->update([
                                    'isactive' => 1,
                                    'approver_by' => auth()->id(),
                                ]);
                                $action->close();
                                $action->dispatch('refreshTable');
                            })
                            ->after(function () {
                                \Filament\Notifications\Notification::make()
                                    ->title('Bài viết đã được duyệt')
                                    ->success()
                                    ->send();
                            }),
                    ]),
                Tables\Actions\Action::make('reviewAndRefuse')
                    ->tooltip('Từ chối bài viết')
                    ->iconButton()
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn($record) => $record->isactive != PostStatus::Rejected->value)
                    ->record(fn($record) => $record)
                    ->modalHeading('Xem chi tiết bài viết')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Đóng')
                    ->infolist([
                        Grid::make(1)->schema([
                            TextEntry::make('name')
                                ->label('Tên bài viết')
                                ->inlineLabel(),

                            TextEntry::make('description')
                                ->label('Mô tả ngắn')
                                ->inlineLabel(),

                            TextEntry::make('category.name')
                                ->label('Danh mục bài viết')
                                ->inlineLabel(),

                            TextEntry::make('link_url')
                                ->label('Link tập tin')
                                ->inlineLabel()->hidden(fn ($record) => !$record->link_url),

                            TextEntry::make('is_home')
                                ->label('Hiển thị lên trang chủ')
                                ->inlineLabel()
                                ->formatStateUsing(fn ($state) => $state ? '✔ Có' : '✘ Không'),
                                TextEntry::make('isactive')
                                ->label('trạng thái')
                                ->inlineLabel()
                                ->formatStateUsing(fn ($state) => PostStatus::tryFrom($state)?->getLabel() ?? ''),

                            TextEntry::make('created_at')
                                ->label('Ngày tạo')
                                ->inlineLabel()
                                ->dateTime('d/m/Y H:i'),

                            TextEntry::make('updated_at')
                                ->label('Ngày sửa')
                                ->inlineLabel()
                                ->dateTime('d/m/Y H:i'),
                        ]),

                        Grid::make()->schema([
                            ImageEntry::make('image')
                                ->label('Hình ảnh đại diện')
                                ->columnSpanFull()
                                ->hidden(fn ($record) => !$record->image),
                        ]),

                        Grid::make()->schema([
                            TextEntry::make('contents')
                                ->label('Nội dung tin tức')
                                ->inlineLabel()
                                ->columnSpanFull()
                                ->extraAttributes([
                                    'style' => 'max-height: 180px; overflow-y: auto; padding: 8px;',
                                ])
                                ->prose()
                                ->html(), // nếu nội dung có thẻ HTML từ CKEditor

                        ]),
                    ])
                    ->modalFooterActions(fn($record) => [
                        Tables\Actions\Action::make('refuse')
                            ->label('Từ chối bài viết')
                            ->color('danger')
                            ->visible(fn() => $record->isactive != PostStatus::Rejected->value)
                            ->form([
                                Forms\Components\Textarea::make('note')
                                    ->label('Lý do từ chối')
                                    ->required()
                                    ->maxLength(500),
                            ])
                            ->action(function (array $data, $record, Tables\Actions\Action $action) {
                                $record->update([
                                    'isactive' => PostStatus::Rejected->value,
                                    'approver_by' => auth()->id(),
                                ]);

                                // 👉 Lưu lý do vào bảng khác nếu có
                                PostApproval::create([
                                    'post_id' => $record->id,
                                    'reason' => $data['note'], // lấy từ textarea
                                    'user_id' => auth()->id(),
                                ]);

                                $action->dispatch('refreshTable');
                                $action->dispatch('close-modal');
                            })
                            ->after(function () {
                                \Filament\Notifications\Notification::make()
                                    ->title('Bài viết đã bị từ chối')
                                    ->warning()
                                    ->send();
                            }),
                    ])

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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getCategoryOptions($categories = null, $prefix = ''): array
    {
        $categories = $categories ?? Menus::whereNull('id_parent')->with('children')->get();

        $result = [];

        foreach ($categories as $category) {
            $result[$category->id] = $prefix . $category->name;

            if ($category->children->count()) {
                $result += self::getCategoryOptions($category->children, $prefix . '— ');
            }
        }

        return $result;
    }

    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }
}
