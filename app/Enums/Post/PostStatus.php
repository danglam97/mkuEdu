<?php

namespace App\Enums\Post;

enum PostStatus: string
{
    case Pending = '0';
    case Approved = '1';
    case Waiting  = '2';
    case Rejected = '3';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Chờ duyệt',
            self::Approved => 'Đã duyệt',
            self::Waiting => 'Chờ duyệt (đã sửa)',
            self::Rejected => 'Từ chối',

        };
    }
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->getLabel(),
        ])->toArray();
    }
}
