<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Operators;

use App\Models\Operator;
use App\Orchid\Layouts\Operators\OperatorListLayout;
use App\Traits\DateHelper;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class OperatorListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $operators = Operator::with('calls')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($operators as $operator) {
            $totalDuration = 0;
            foreach ($operator->calls as $call) {

                $duration = (float) $call->call_duration;

                if ($duration < 30)
                {
                    continue;
                }

                $totalDuration += $duration;
            }

            $workedHours = $totalDuration / 3600;
            $workedHours = min($workedHours, 11);

            $workedMinutes = $totalDuration / 60;

            $operator->worked_hours = number_format($workedHours, 1) . ' ч';
            $operator->worked_minutes = number_format($workedMinutes, 1) . ' мин';
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
}
