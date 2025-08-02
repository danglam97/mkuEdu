@php
    $record = $getState(); // <-- gán tường minh cho dễ dùng

@endphp
<div class="space-y-6">
    {{-- Thông tin cơ bản --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h3 class="text-sm font-medium text-gray-500">Tên bài viết</h3>
            <p class="text-base text-gray-800">{{ $record->name }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500 font-medium">
                Danh Mục:
                <span class="text-base text-gray-800">
            {{ $record->category?->name }}
        </span>
            </p>
        </div>

        <div>
            <h3 class="text-sm font-medium text-gray-500">Mô tả ngắn</h3>
            <p class="text-base text-gray-800">{{ $record->description }}</p>
        </div>

        @if ($record->link_url)
            <div>
                <h3 class="text-sm font-medium text-gray-500">Link tập tin</h3>
                <p class="text-base text-blue-700 underline"><a href="{{ $record->link_url }}" target="_blank">{{ $record->link_url }}</a></p>
            </div>
        @endif
        <div>
            <p class="text-sm text-gray-500 font-medium">
                Hiển thị trang chủ:
                <span class="text-base text-gray-800">
            {{ $record->is_home ? '✔ Có' : '✘ Không' }}
        </span>
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500 font-medium">
                Trạng thái bài viết:
                <span class="text-base text-gray-800">
            {{ \App\Enums\Post\PostStatus::tryFrom($record->status)?->getLabel() ?? 'Không xác định' }}
        </span>
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">
                Trạng thái hoạt động:
                <span class="text-base text-gray-800">
            {{ \App\Enums\Post\PostIsActive::tryFrom($record->isactive)?->getLabel() ?? 'Không xác định' }}
        </span>
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium mt-1">
                Ngày bắt đầu:
                <span class="text-base text-gray-800">
            {{ $record->start_datetime ? $record->start_datetime->format('d/m/Y H:i') : 'Chưa đặt' }}
        </span>
            </p>

            <p class="text-sm text-gray-500 font-medium mt-1">
                Ngày kết thúc:
                <span class="text-base text-gray-800">
            {{ $record->end_datetime ? $record->end_datetime->format('d/m/Y H:i') : 'Chưa đặt' }}
        </span>
            </p>
        </div>


    </div>

    {{-- Hình ảnh đại diện --}}
    @if ($record->image)
        <div class="flex items-center gap-2">
            <p class="text-sm text-gray-500 font-medium mb-0">Hình ảnh đại diện:</p>
            <img src="{{ asset('storage/' . $record->image) }}" class="rounded-lg border w-32 h-auto">
        </div>
    @endif

    {{-- Nội dung bài viết --}}
    <div>
        <h3 class="text-sm font-medium text-gray-500 mb-2">Nội dung</h3>
        <div class="prose max-w-full overflow-y-auto rounded-xl"
             style="max-height: 500px;">
            {!! $record->contents !!}
        </div>
    </div>
</div>
