<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\Department;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Thông tin cá nhân')
                ->schema([
                    TextInput::make('name')
                        ->label('Họ và Tên')
                        ->required()
                        ->placeholder('Nhập họ và tên đầy đủ')
                        ->maxLength(255)
                        ->validationMessages([
                            'required' => 'Vui lòng nhập họ và tên.',
                            'max' => 'Họ và tên không được vượt quá :max ký tự.',
                        ]),

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

            Step::make('Thông tin tài khoản')
                ->schema([
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->rules(['unique:users,email'])
                        ->placeholder('Nhập địa chỉ email')
                        ->validationMessages([
                            'unique' => 'Email này đã được sử dụng.',
                            'required' => 'Vui lòng nhập Email.',
                        ]),

                    TextInput::make('password')
                        ->revealable()
                        ->password()
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Nhập mật khẩu'),

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
