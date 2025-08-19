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

class ApprovePost extends ViewRecord
{
	protected static string $resource = PostResource::class;

	protected function getHeaderActions(): array
	{
		return [
			Actions\Action::make('approve')
				->label('Duyệt bài viết')
				->color('success')
				->icon('heroicon-o-check-circle')
				->size('lg')
				->visible(fn($record) => Auth::user()?->can('approve', $record))
				->form([
					Forms\Components\Section::make('Thông tin duyệt')
						->description('Nhập thông tin để duyệt bài viết')
						->icon('heroicon-o-calendar')
						->schema([
							Forms\Components\DateTimePicker::make('approved_publish_date')
								->label('Ngày đăng được duyệt')
								->helperText('Người duyệt có thể thay đổi ngày đăng bài')
								->default(fn($record) => $record->publish_date ?? now())
								->native(false)
								->seconds(false)
								->displayFormat('d/m/Y')
								->required()
								->columnSpanFull(),
							
							Forms\Components\Textarea::make('approval_note')
								->label('Nội dung phê duyệt')
								->helperText('Nhập nhận xét hoặc yêu cầu chỉnh sửa (nếu có)')
								->rows(4)
								->placeholder('Bài viết được viết tốt, nội dung phù hợp...')
								->required()
								->columnSpanFull(),
						])
						->columns(1),
				])
				->modalHeading('Duyệt bài viết')
				->modalDescription('Xác nhận thông tin để duyệt bài viết này')
				->modalSubmitActionLabel('Duyệt bài viết')
				->modalCancelActionLabel('Hủy bỏ')
				->action(function (array $data) {
					$this->record->update([
						'status' => PostStatus::Approved->value,
						'approver_by' => Auth::id(),
						'isactive' => PostIsActive::Approved->value,
						'approved_publish_date' => $data['approved_publish_date'],
						'approval_note' => $data['approval_note'],
					]);
					\Filament\Notifications\Notification::make()
						->title('🎉 Bài viết đã được duyệt thành công!')
						->body('Bài viết sẽ được hiển thị theo lịch trình đã duyệt.')
						->success()
						->send();
					$this->redirect(PostResource::getUrl('index'));
				}),

			Actions\Action::make('back')
				->label('Quay lại danh sách')
				->icon('heroicon-o-arrow-left')
				->color('gray')
				->size('lg')
				->url(PostResource::getUrl('index')),
		];
	}

	public function infolist(Infolist $infolist): Infolist
	{
		return $infolist
			->schema([
				Section::make('Thông tin cần duyệt')
					->description('Xem xét thông tin bài viết trước khi duyệt')
					->icon('heroicon-o-clipboard-document-list')
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

				Section::make('Hình ảnh đại diện')
					->description('Kiểm tra hình ảnh của bài viết')
					->icon('heroicon-o-photo')
					->schema([
						ImageEntry::make('image')
							->label('Hình ảnh đại diện')
							->disk('public')
							->size(250)
							->circular()
							->columnSpanFull(),
					])
					->collapsible()
					->collapsed(false),

				Section::make('Nội dung chi tiết')
					->description('Đọc và đánh giá nội dung bài viết')
					->icon('heroicon-o-document-text')
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

				Section::make('Thông tin tác giả')
					->description('Thông tin về người tạo bài viết')
					->icon('heroicon-o-user')
					->schema([
						Grid::make(3)
							->schema([
								TextEntry::make('createdBy.name')
									->label('Người tạo')
									->badge()
									->color('success'),
								TextEntry::make('created_at')
									->label('Ngày tạo')
									->dateTime('d/m/Y')
									->badge()
									->color('gray'),
								TextEntry::make('publish_date')
									->label('Ngày đăng dự kiến')
									->dateTime('d/m/Y')
									->badge()
									->color('warning'),
							])
					])
					->collapsible()
					->collapsed(true),
			]);
	}
}
