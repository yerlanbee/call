<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Operators;

use App\Models\Call;
use App\Models\Operator;
use App\Orchid\Layouts\Operators\OperatorListLayout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class OperatorListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $operators = Operator::with(['calls' => function ($query) {
            $query->orderBy('date_time');
        }])->orderBy('id', 'desc')->get();

        foreach ($operators as $operator) {
            /**
             * @var Operator $operator
             */
            $totalSeconds = $operator->getTotalSecondsByOperator();
            $call = new Call;

            $operator->hours = $call->toHM($totalSeconds);
            $operator->minutes = $call->toM($totalSeconds);
        }

        return [
            'operators' => $operators,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Список операторов';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Полный список операторов';
    }


    /**
     * The screen's layout elements.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            OperatorListLayout::class,
        ];
    }

    public function remove(Request $request): void
    {
        Toast::info(__('Успешно удален.'));
    }

    private function excelToCarbon(float $excelDate): Carbon
    {
        return Carbon::createFromTimestampUTC(($excelDate - 25569) * 86400);
    }
}
