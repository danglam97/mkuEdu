<?php

namespace App\Filament\Admin\Resources\InternationalPostResource\Pages;

use App\Filament\Admin\Resources\InternationalPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ActionEntry;

class ViewInternationalPost extends ViewRecord
{
    protected static string $resource = InternationalPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Thông tin bài viết')
                    ->schema([
                        ImageEntry::make('image')
                            ->label('Hình ảnh đại diện')
                            ->circular()
                            ->size(200),
                        
                        TextEntry::make('name')
                            ->label('Tiêu đề')
                            ->size(TextEntry\TextEntrySize::Large)
                            ->weight('bold'),
                        
                        TextEntry::make('description')
                            ->label('Mô tả ngắn')
                            ->markdown()
                            ->columnSpanFull(),
                        
                        TextEntry::make('contents')
                            ->label('Nội dung chi tiết')
                            ->html()
                            ->columnSpanFull(),
                        
                        TextEntry::make('link_url')
                            ->label('Link tập tin')
                            ->url(fn ($record) => $record->link_url)
                            ->openUrlInNewTab(),
                        
                            TextEntry::make('slug_category')
                            ->label('Danh mục')
                            ->formatStateUsing(function ($state) {
                                return match ($state) {
                                    'hop-tac-quoc-te' => 'Hợp tác quốc tế',
                                    default => $state,
                                };
                            }),
                     TextEntry::make('copy_url')
                        ->label('Đường dẫn bài viết')
                        ->state(function ($record) {
                            return url("/tin-tuc/{$record->slug_category}/{$record->slug}");
                        })
                        ->copyable()
                        ->copyMessage('Đã sao chép!')
                        ->copyMessageDuration(1500)
                        ->columnSpanFull()
                        
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                Section::make('Thông tin SEO')
                    ->schema([
                        TextEntry::make('meta_title')
                            ->label('Meta Title'),
                        
                        TextEntry::make('meta_keyword')
                            ->label('Meta Keywords'),
                        
                        TextEntry::make('meta_description')
                            ->label('Meta Description')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
                
                Section::make('Thống kê và trạng thái')
                    ->schema([
                        TextEntry::make('total_view')
                            ->label('Số lượt xem')
                            ->badge()
                            ->color('gray'),
                        
                        IconEntry::make('is_home')
                            ->label('Hiển thị trang chủ')
                            ->boolean(),
                        
                        IconEntry::make('isactive')
                            ->label('Kích hoạt')
                            ->boolean(),
                        
                        IconEntry::make('isdelete')
                            ->label('Đánh dấu xóa')
                            ->boolean(),
                        
                        TextEntry::make('status')
                            ->label('Trạng thái')
                            ->badge()
                            ->color(fn (int $state): string => match($state) {
                                0, 2 => 'warning',
                                1 => 'success',
                                3 => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (int $state): string => match($state) {
                                0 => 'Chờ duyệt',
                                1 => 'Đã đăng',
                                2 => 'Chờ duyệt (đã sửa)',
                                3 => 'Từ chối',
                                default => 'Không xác định',
                            }),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
                
                Section::make('Thông tin hệ thống')
                    ->schema([
                        TextEntry::make('createdBy.name')
                            ->label('Người tạo'),
                        
                        TextEntry::make('updatedBy.name')
                            ->label('Người sửa cuối'),
                        
                        TextEntry::make('approverBy.name')
                            ->label('Người duyệt'),
                        
                        TextEntry::make('created_date')
                            ->label('Ngày tạo')
                            ->dateTime('d/m/Y H:i:s'),
                        
                        TextEntry::make('updated_date')
                            ->label('Ngày sửa cuối')
                            ->dateTime('d/m/Y H:i:s'),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
} 