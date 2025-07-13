<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Calls;

use App\Models\Call;
use App\Models\Operator;
use App\Orchid\Filters\DateFilter;
use App\Orchid\Filters\OperatorFilter;
use App\Orchid\Layouts\Calls\CallListLayout;
use App\Orchid\Layouts\OperatorSelection;
use Carbon\Carbon;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CallListScreen extends Screen
{
    public array $data = [];

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $filter = request()->input('filter') ?? null;

        $operatorIds = $filter['operator'] ?? [];
        $date = $filter['date'] ?? null;

        // Список звонков.
        $calls = Call::filters([OperatorFilter::class, DateFilter::class])
            ->when($operatorIds, function ($query, $operatorIds) {
                return $query->whereIn('operator_id', $operatorIds);
            })
            ->when($date, function ($query, $date) {
                $date = Carbon::parse($date);

                $from = $date->copy()->startOfMonth()->format('Y-m-d');
                $to = $date->copy()->endOfMonth()->format('Y-m-d');

                $query->whereBetween('date', [
                    $from,
                    $to
                ]);
            });

        $this->data['total_calls'] = 0;
        $this->data['total_seconds'] = 0;

        $previousEnd = null;

        foreach ($calls->get() as $call) {

            $start = Carbon::parse($call->date_time);
            $end = $start->copy()->addSeconds(ceil((float) $call->call_duration)); // Дата и время окончания разговора.

            if ($previousEnd) {
                $gapInSeconds = $previousEnd->diffInSeconds($end);

                if ($gapInSeconds > 0 && $gapInSeconds <= 480) {
                    $this->data['total_calls'] += 1;
                    $this->data['total_seconds'] += $gapInSeconds;
                }
            }

            $previousEnd = $end;
        }

        return [
            'calls' => $calls->paginate(20),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Список звонков';
    }


    /**
     * The screen's layout elements.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        $data['total_duration'] = (new Call)->toHMS($this->data['total_seconds']);
        $data['total_calls'] = $this->data['total_calls'];
        return [
            Layout::view('calls.description', ['data' => $data]),
            OperatorSelection::class,
            CallListLayout::class,
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Удалить все записи')
                ->icon('trash')
                ->confirm('Вы уверены, что хотите удалить все записи?')
                ->method('deleteAll'),

            Link::make(__('Импортировать CSV'))
                ->icon('download')
                ->route('platform.systems.calls.import')
        ];
    }

    public function deleteAll(): void
    {
        Call::query()->truncate(); // очищает всю таблицу

        Operator::query()->truncate();

        Toast::info(__('Успешно удалили все записи.'));
    }
}
