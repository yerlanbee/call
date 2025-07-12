<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Operators;

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
            $totalSeconds = 0;
            $previousEnd = null;

            foreach ($operator->calls as $call) {
                $start = Carbon::parse($call->date_time);
                $end = $start->copy()->addSeconds((float) $call->call_duration);

                if ($previousEnd) {
                    $gap = $start->diffInSeconds($previousEnd);
                    if ($gap > 480) {
                        $previousEnd = $end;
                        continue;
                    }
                }

                $totalSeconds += $call->call_duration;
                $previousEnd = $end;
            }

            $hours = floor($totalSeconds / 3600);
            $minutesOnly = floor(($totalSeconds % 3600) / 60);
            $totalMinutes = floor($totalSeconds / 60);

            $operator->hours = "{$hours} ч {$minutesOnly} мин";
            $operator->minutes = "$totalMinutes мин";
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
