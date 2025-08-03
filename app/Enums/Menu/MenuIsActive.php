<?php

namespace App\Enums\Menu;

enum MenuIsActive: string
{
    case Pending = '0';
    case Approved = '1';
    case slug = 'dao-tao';

    public function getLabel(): string
    {
        return match ($this) {
            self::Approved => 'Hoạt Động',
            self::Pending => 'Không hoạt động',

        };
    }
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->getLabel(),
        ])->toArray();
    }
}
