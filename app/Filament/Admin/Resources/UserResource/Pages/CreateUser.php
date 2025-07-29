<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
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
            Step::make('Họ và Tên')
                ->schema([
                    TextInput::make('name')
                        ->label('Họ và Tên')
                        ->required()
                        ->validationMessages([
                            'required' => 'Vui lòng nhập họ và tên.',
                            'max' => 'Họ và tên không được vượt quá :max ký tự.',
                        ])
                        ->placeholder('Nhập họ và tên')
                        ->maxLength(255),
                ]),
            Step::make('Email')
                ->schema([
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->rules(['unique:users,email'])
                        ->placeholder('Nhập email')
                        ->validationMessages([
                            'unique' => 'Email này đã được sử dụng',
                            'required'=>'Vui lòng nhập Email'
                        ]),
                    TextInput::make('password')
                        ->revealable()
                        ->password()
                        ->required()
                        ->maxLength(255),
                ]),
            Step::make('Phân quyền')
                ->schema([
                    Select::make('roles') // đảm bảo model User có quan hệ với Role
                    ->label('Chọn quyền')
                        ->multiple()
                        ->relationship('roles', 'name') // Nếu dùng Spatie + hasManyThrough
                        ->preload()
                        ->required()
                        ->validationMessages([
                            'required' => 'Vui lòng chọn ít nhất một quyền.'
                        ]),
                ]),
        ];
    }
}
