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
        <div x-data="{ copied: false }" class="flex items-center gap-2 mt-1">
        <p class="text-sm text-gray-500 font-medium">Đường dẫn bài viết:</p>
        <input type="text"
               readonly
               :value="'{{ url("/tin-tuc/{$record->category->slug}/{$record->slug}") }}'"
               class="text-sm text-gray-800 bg-gray-100 border rounded px-2 py-1 w-full max-w-xs cursor-text"
               x-ref="postUrl"
        >
        <button
            @click="navigator.clipboard.writeText($refs.postUrl.value); copied = true; setTimeout(() => copied = false, 2000)"
            class="text-blue-600 hover:text-blue-800 transition"
            title="Sao chép link bài viết"
        >
            📋
        </button>
        <span x-show="copied" x-transition class="text-green-600 text-sm">Đã sao chép!</span>
    </div>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">
                Trạng thái hoạt động:
                <span class="text-base text-gray-800">
            {{ \App\Enums\Post\PostIsActive::tryFrom($record->isactive)?->getLabel() ?? 'Không xác định' }}
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
