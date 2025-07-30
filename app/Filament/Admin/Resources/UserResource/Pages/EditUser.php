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
                    Select::make('department_id')
                        ->label('Phòng ban')
                        ->options(function () {
                            return \App\Models\Department::all()->mapWithKeys(function ($dept) {
                                return [$dept->id => str_repeat('— ', $dept->depth) . $dept->name];
                            })->toArray();
                        })
                        ->searchable()
                        ->placeholder('Chọn phòng ban'),

                    TextInput::make('phone')
                        ->label('Số điện thoại')
                        ->tel()
                        ->maxLength(20)
                        ->placeholder('VD: 0912xxxxxx'),

                    TextInput::make('address')
                        ->label('Địa chỉ')
                        ->maxLength(255)
                        ->placeholder('Nhập địa chỉ nơi làm việc hoặc cư trú'),
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
                    Select::make('roles')
                        ->label('Chọn quyền')
                        ->multiple()
                        ->relationship('roles', 'name')
                        ->preload()
                        ->placeholder('Chọn một hoặc nhiều quyền')
                        ->required()
                        ->validationMessages([
                            'required' => 'Vui lòng chọn ít nhất một quyền.',
                        ]),
                ]),
        ];
    }
}
