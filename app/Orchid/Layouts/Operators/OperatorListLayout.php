<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Operators;

use App\Models\Call;
use App\Models\Operator;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class OperatorListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'operators';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('username', 'Оператор')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Operator $call) => $call->username),

            TD::make('created_at', 'Создан')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Operator $call) => $call->created_at),

        ];
    }
}
