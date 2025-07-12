<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Calls;

use App\Models\Call;
use App\Models\Operator;
use Carbon\Carbon;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CallListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'calls';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('operator', 'Оператор')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_SELECT,
                    Operator::query()->pluck('username', 'id')->toArray(),
                )
                ->render(fn (Call $call) => $call->operator->username),

            TD::make('call_duration', 'Длительность звонка')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Call $call) => $call->convertDurationToTime()),

            TD::make('date', 'Дата')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Call $call) => $call->toDate()),

            TD::make('date', 'Дата и время')
                ->sort()
                ->cantHide()
                ->render(fn (Call $call) => $call->date_time),

            TD::make('created_at', 'Создан')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Call $call) => $call->created_at),
        ];
    }
}
