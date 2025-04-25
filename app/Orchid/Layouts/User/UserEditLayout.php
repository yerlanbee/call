<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class UserEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('user.name')
                ->type('text')
                ->max(255)
                ->title('Имя')
                ->placeholder(__('Имя')),

            Input::make('user.last_name')
                ->type('text')
                ->max(255)
                ->title('Фамилия')
                ->placeholder(__('Фамилия')),

            Input::make('user.phone')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Номер телефона')
                ->placeholder(__('7XXX7707707')),

            Input::make('user.email')
                ->type('email')
                ->title('Почта')
                ->placeholder(__('Email')),
        ];
    }
}
