<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Operators;

use App\Models\Operator;
use App\Orchid\Layouts\Operators\OperatorListLayout;
use App\Traits\DateHelper;
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
        $workStart = Carbon::parse('09:00:00');
        $workEnd = Carbon::parse('19:00:00');

        $operators = Operator::with('calls')->orderBy('id', 'desc')->get();

        foreach ($operators as $operator) {
            // Общие минуты за день
            $dailyMinutes = 0;

            // Группируем звонки по дате
            $callsByDate = $operator->calls->groupBy(function ($call) {
                return $this->excelToCarbon((float) $call->date_time)->toDateString();
            });

            $operator->worked_days = [];

            foreach ($callsByDate as $date => $calls) {
                $intervalsWorked = 0;

                // Подготовка звонков с временем
                $callsWithTime = $calls->map(function ($call) {
                    $call->carbon_time = $this->excelToCarbon((float) $call->date_time);
                    return $call;
                });

                // Старт и конец рабочего дня (на конкретную дату)
                $start = Carbon::parse($date . ' ' . $workStart->format('H:i:s'));
                $end = Carbon::parse($date . ' ' . $workEnd->format('H:i:s'));

                // Цикл по 15-мин интервалам
                $current = $start->copy();
                while ($current < $end) {
                    $next = $current->copy()->addMinutes(15);

                    // Проверяем, был ли звонок в интервале
                    $hasCall = $callsWithTime->contains(function ($call) use ($current, $next) {
                        return $call->carbon_time->betweenIncluded($current, $next);
                    });

                    if ($hasCall) {
                        // Засчитываем интервал
                        $intervalsWorked++;
                    }

                    $current = $next;
                }

                $minutesWorked = $intervalsWorked * 15;
                $dailyMinutes += $minutesWorked;
            }

            $seconds = $operator->calls->sum(function ($call) {
                return (float) $call->call_duration;
            });

            $operator->worked_hours = number_format($dailyMinutes / 60, 1) . ' ч';
            $operator->worked_minutes = number_format(($seconds / 60), 1) . ' мин';
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
