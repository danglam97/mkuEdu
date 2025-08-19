<?php

namespace App\Filament\Admin\Resources\PostResource\Pages;

use App\Enums\Post\PostIsActive;
use App\Enums\Post\PostStatus;
use App\Filament\Admin\Resources\PostResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewPost extends ViewRecord
{
	protected static string $resource = PostResource::class;

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
				Section::make('Thông tin chính')
					->description('Thông tin cơ bản của bài viết')
					->icon('heroicon-o-document-text')
					->schema([
						Grid::make(2)
							->schema([
								TextEntry::make('name')
									->label('Tên bài viết')
									->size(TextEntry\TextEntrySize::Large)
									->weight('bold')
									->color('primary'),
								TextEntry::make('category.name')
									->label('Danh mục')
									->badge()
									->color('info'),
							])
						,
						TextEntry::make('description')
							->label('Mô tả ngắn')
							->html()
							->columnSpanFull()
							->markdown(),
					])
					->collapsible()
					->collapsed(false),

				Section::make('Hình ảnh')
					->description('Hình ảnh đại diện của bài viết')
					->icon('heroicon-o-photo')
					->schema([
						ImageEntry::make('image')
							->label('Hình ảnh đại diện')
							->disk('public')
							->size(300)
							->circular()
							->columnSpanFull(),
					])
					->collapsible()
					->collapsed(false),

				Section::make('Nội dung chi tiết')
					->description('Nội dung đầy đủ của bài viết')
					->icon('heroicon-o-document')
					->schema([
						TextEntry::make('contents')
							->label('Nội dung bài viết')
							->html()
							->columnSpanFull()
							->prose()
							->markdown(),
					])
					->collapsible()
					->collapsed(false),

				Section::make('Thông tin hệ thống')
					->description('Thông tin về người tạo và thời gian')
					->icon('heroicon-o-information-circle')
					->schema([
						Grid::make(3)
							->schema([
								TextEntry::make('createdBy.name')
									->label('Người tạo')
									->badge()
									->color('success'),
								TextEntry::make('created_at')
									->label('Ngày tạo')
									->dateTime('d/m/Y H:i')
									->badge()
									->color('gray'),
								TextEntry::make('publish_date')
									->label('Ngày đăng dự kiến')
									->dateTime('d/m/Y H:i')
									->badge()
									->color('warning'),
							])
					])
					->collapsible()
					->collapsed(true),


			]);
	}
}
