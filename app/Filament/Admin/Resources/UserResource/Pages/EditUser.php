<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    use EditRecord\Concerns\HasWizard; // ✅ Thêm dòng này để dùng Steps

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Họ và Tên')
                ->schema([
                    TextInput::make('name')
                        ->label('Họ và Tên')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Nhập họ và tên'),
                ]),
            Step::make('Email')
                ->schema([
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Nhập email')
                        ->unique(ignoreRecord: true), // ✅ Cho phép giữ lại email cũ
                    TextInput::make('password')
                        ->label('Mật khẩu mới')
                        ->revealable()
                        ->password()
                        ->maxLength(255)
                        ->dehydrated(fn ($state) => filled($state)) // ✅ Chỉ lưu khi có nhập
                        ->nullable(),
                ]),
            Step::make('Phân quyền')
                ->schema([
                    Select::make('roles')
                        ->label('Chọn quyền')
                        ->multiple()
                        ->relationship('roles', 'name')
                        ->preload()
                        ->required(),
                ]),
        ];
    }
}
