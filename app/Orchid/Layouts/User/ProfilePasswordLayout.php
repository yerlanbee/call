<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Layouts\Rows;

class ProfilePasswordLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Password::make('old_password')
                ->placeholder('Введите текущий пароль')
                ->title('Текущий пароль'),

            Password::make('password')
                ->placeholder('Введите пароль, который будет установлен')
                ->title('Новый пароль'),

            Password::make('password_confirmation')
                ->placeholder('Введите пароль, который будет установлен')
                ->title('Подтвердите новый пароль')
        ];
    }
}
