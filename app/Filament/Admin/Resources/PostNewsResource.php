<?php

namespace App\Filament\Admin\Resources;

use App\Enums\Post\PostIsActive;
use App\Enums\Post\PostStatus;
use App\Filament\Admin\Resources\PostNewsResource\Pages;
use App\Filament\Admin\Resources\PostNewsResource\RelationManagers;
use App\Forms\Components\CKEditor;
use App\Models\CategoryNews;
use App\Models\PostNews;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostNewsResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;

    protected static ?string $model = PostNews::class;

    protected static ?string $modelLabel = 'Bài viết tin tức';
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $activeNavigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Quản lý tin tức';
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
                                        ->maxLength(500),

                                    Forms\Components\RichEditor::make('description')
                                        ->label('Mô tả ngắn')
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

                                    Forms\Components\FileUpload::make('image')
                                        ->label('Hình ảnh đại diện')
                                        ->image()
                                        ->disk('public')
                                        ->directory('news/images')
                                        ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp']),

                                    Forms\Components\Select::make('id_category')
                                        ->label('Danh mục tin')
                                        ->options(self::getCategoryOptions())
                                        ->searchable()
                                        ->preload()
                                        ->required(),

                                    CKEditor::make('contents')
                                        ->label('Nội dung tin tức')
                                        ->required(),

                                    Forms\Components\TextInput::make('link_url')
                                        ->label('Link tập tin')
                                        ->maxLength(550),

                                    Forms\Components\Grid::make(2) // 2 cột
                                    ->schema([
                                        Forms\Components\Toggle::make('is_home')
                                            ->label('Hiển thị lên trang chủ')
                                            ->inline(),

                                        Forms\Components\Toggle::make('isactive')
                                            ->label('Trạng thái hiển thị')
                                            ->inline()
                                            ->disabled(),
                                    ]),
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
                    ->limit(30) // giới hạn ký tự hiển thị
                    ->tooltip(fn ($record) => $record->name) // tooltip khi hover
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Hình ảnh')
                    ->disk('public'), // tùy thuộc cấu hình filesystem

                Tables\Columns\TextColumn::make('category.name') // nếu bạn có quan hệ ->category()
                ->label('Danh mục')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('isactive')
                    ->label('Trạng thái hoạt động')
                    ->formatStateUsing(fn($state) => PostIsActive::tryFrom($state)?->getLabel() ?? '')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái bài viết')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => PostStatus::tryFrom($state)?->getLabel() ?? ''),
            ]) ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('id_category')
                    ->label('Danh mục tin tức')
                    ->options(CategoryNews::pluck('name', 'id')->toArray())->searchable(),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options(PostStatus::options()),
                Tables\Filters\SelectFilter::make('isactive')
                    ->label('Trạng thái hiển thị')
                    ->options(PostIsActive::options())
            ])
            ->actions([
                Tables\Actions\Action::make('copy_link')
                ->label('Copy link')->iconButton()
                ->icon('heroicon-o-clipboard')
                ->color('gray')
                ->tooltip('Hiển thị đường dẫn để copy')
                ->action(function ($record) {
                    $link = url("/tin-tuc/{$record->category->slug}/{$record->slug}");

                    \Filament\Notifications\Notification::make()
                        ->title('Sao chép link bài viết')
                        ->body("Bạn có thể sao chép đường dẫn sau:<br><strong>{$link}</strong>")
                        ->success()
                        ->send();
                }),
                Tables\Actions\ViewAction::make()
                    ->tooltip('Xem chi tiết')
                    ->iconButton()
                    ->modalHeading('Thông tin bài viết')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Đóng')
                    ->infolist([
                        ViewEntry::make('record')
                            ->label(false)
                            ->state(fn ($record) => $record)
                            ->view('filament.admin.posts_new.partials.contents'),
                    ]),

                Tables\Actions\EditAction::make()
                    ->tooltip('chỉnh sửa')
//                    ->visible(fn($record) =>
//                    in_array($record->status, [
//                        PostStatus::Pending->value,
//                        PostStatus::Waiting->value,
//                        PostStatus::Rejected->value
//                    ])
//                    )
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()->tooltip('xóa')->iconButton() ->successNotificationTitle('Đã xóa bài viết thành công'),
                Tables\Actions\Action::make('approve')
                    ->tooltip('Duyệt bài viết')
                    ->iconButton()
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->authorize(fn($record) => auth()->user()->can('approve', $record)) // ✅ Check Policy + trạng thái
                    ->modalHeading('Xem chi tiết bài viết')
                    ->modalSubmitAction(false) // Không có nút submit mặc định
                    ->modalCancelActionLabel('Đóng')
                    ->infolist([
                        ViewEntry::make('record')
                            ->label(false)
                            ->state(fn ($record) => $record)
                            ->view('filament.admin.posts_events.partials.contents'),
                    ])
                    ->modalFooterActions(fn($record) => [
                        Tables\Actions\Action::make('confirmApprove')
                            ->label('Xác nhận duyệt')
                            ->color('success')
                            ->authorize(fn($record) => auth()->user()->can('approve', $record)) // Check lại trong nút confirm
                            ->action(function ($record, Tables\Actions\Action $action) {
                                $record->update([
                                    'status' => PostStatus::Approved->value,
                                    'approver_by' => auth()->id(),
                                    'isactive' => PostIsActive::Approved->value,
                                ]);
                                $action->close();
                                $action->dispatch('refreshTable');
                                \Filament\Notifications\Notification::make()
                                    ->title('Bài viết đã được duyệt')
                                    ->success()
                                    ->send();
                            }),
                    ]),

                Tables\Actions\Action::make('refuse')
                    ->tooltip('Từ chối bài viết')
                    ->iconButton()
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->authorize(fn($record) => auth()->user()->can('refuse', $record))
                    ->record(fn($record) => $record)
                    ->modalHeading('Xem chi tiết bài viết')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Đóng')
                    ->infolist([
                        ViewEntry::make('record')
                            ->label(false)
                            ->state(fn ($record) => $record)
                            ->view('filament.admin.posts_events.partials.contents'),
                    ])
                    ->modalFooterActions(fn($record) => [
                        Tables\Actions\Action::make('confirmRefuse')
                            ->label('Từ chối bài viết')
                            ->color('danger')
                            ->authorize(fn($record) => auth()->user()->can('refuse', $record))
                            ->action(function (array $data, $record, Tables\Actions\Action $action) {
                                $record->update([
                                    'status' => PostStatus::Rejected->value,
                                    'approver_by' => auth()->id(),
                                    'isactive' => PostStatus::Pending->value,
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
            'index' => Pages\ListPostNews::route('/'),
            'create' => Pages\CreatePostNews::route('/create'),
            'edit' => Pages\EditPostNews::route('/{record}/edit'),
        ];
    }

    public static function getCategoryOptions($categories = null, $prefix = ''): array
    {
        $categories = $categories ?? CategoryNews::whereNull('id_parent')->with('children')->get();

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
