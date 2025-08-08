<?php

namespace App\Filament\Admin\Resources\BannerResource\Pages;

use App\Filament\Admin\Resources\BannerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;

class ViewBanner extends ViewRecord
{
    protected static string $resource = BannerResource::class;

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
                Section::make('Thông tin banner')
                    ->schema([
                        ImageEntry::make('image')
                            ->label('Ảnh banner')
                            ->circular()
                            ->size(200),
                        
                        TextEntry::make('title')
                            ->label('Tiêu đề')
                            ->size(TextEntry\TextEntrySize::Large)
                            ->weight('bold'),
                        
                        TextEntry::make('description')
                            ->label('Mô tả')
                            ->markdown()
                            ->columnSpanFull(),
                        
                        TextEntry::make('link')
                            ->label('Liên kết')
                            ->url(fn ($record) => $record->link)
                            ->openUrlInNewTab(),
                        
                        TextEntry::make('position')
                            ->label('Vị trí hiển thị')
                            ->badge()
                            ->color('info'),
                        
                        TextEntry::make('order')
                            ->label('Thứ tự')
                            ->badge()
                            ->color('gray'),
                        
                        IconEntry::make('is_active')
                            ->label('Trạng thái')
                            ->boolean()
                            ->trueIcon('heroicon-o-eye')
                            ->falseIcon('heroicon-o-eye-slash')
                            ->trueColor('success')
                            ->falseColor('danger'),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                Section::make('Thông tin hệ thống')
                    ->schema([
                        TextEntry::make('createdBy.name')
                            ->label('Người tạo'),
                        
                        TextEntry::make('updatedBy.name')
                            ->label('Người sửa cuối'),
                        
                        TextEntry::make('created_at')
                            ->label('Ngày tạo')
                            ->dateTime('d/m/Y H:i:s'),
                        
                        TextEntry::make('updated_at')
                            ->label('Ngày sửa cuối')
                            ->dateTime('d/m/Y H:i:s'),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
} 