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
        $operators = Operator::with('calls')->orderBy('id', 'desc')->get();

        foreach ($operators as $operator) {
            $calls = $operator->calls;

            $processedCalls = [];

            foreach ($calls as $call) {
                try {
                    $ts = Date::excelToDateTimeObject($call->date_time)->getTimestamp();

                    $_duration = Date::excelToDateTimeObject($call->call_duration)->getTimestamp();
                    $duration = date('H', $_duration) * 3600 + date('i', $_duration) * 60 + date('s', $_duration);

                    $processedCalls[] = [
                        'timestamp' => $ts,
                        'duration' => $duration,
                    ];
                } catch (\Exception $e) {
                    // Пропускаем некорректные записи
                    continue;
                }
            }

            // Сортируем звонки по времени
            usort($processedCalls, fn($a, $b) => $a['timestamp'] <=> $b['timestamp']);

            $totalSeconds = 0;
            $earliest = PHP_INT_MAX;
            $latest = 0;
            $lastTs = null;
            $lastDuration = 0;

            foreach ($processedCalls as $call) {
                $ts = $call['timestamp'];
                $duration = $call['duration'];

                if ($lastTs !== null) {
                    $gap = $ts - $lastTs - $lastDuration;

                    if ($gap > 930) { // Новый рабочий блок
                        $totalSeconds += ($latest - $earliest + $lastDuration);
                        $earliest = $ts;
                        $latest = $ts;
                    }
                }

                $lastTs = $ts;
                $lastDuration = $duration;

                if ($ts < $earliest) $earliest = $ts;
                if ($ts > $latest) $latest = $ts;
            }

            // Добавляем последний интервал
            $totalSeconds += ($latest - $earliest);

            // Перевод в часы и минуты
            $workedHours = min($totalSeconds / 3600, 11); // максимум 11 ч
            $workedMinutes = ($totalSeconds / 60);

            // Запись в объект оператора
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
