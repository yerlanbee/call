<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Calls;

use App\Models\Call;
use App\Models\Operator;
use App\Orchid\Filters\DateFilter;
use App\Orchid\Filters\OperatorFilter;
use App\Orchid\Layouts\Calls\CallListLayout;
use App\Orchid\Layouts\OperatorSelection;
use App\Traits\DateHelper;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CallListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'calls' => Call::filters([OperatorFilter::class, DateFilter::class])->with('operator')
                ->orderBy('id', 'desc')
                ->paginate(15),

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
        $date = request()->input('date') ?? null;

        $operator = request()->input('operator_id') ?? null;

        $calls = Call::getByOperatorOrDate((int) $operator, $date);

        $counts = $this->getTime($calls);

        $data = [
            'total_calls' => $counts['total_calls'],
            'total_duration' => $counts['total_duration'],
        ];

        if ($date)
        {

            $perDay = $this->getTime($calls->where('date', DateHelper::toExcel($date)));

            $data['total_calls'] = $perDay['total_calls'];
            $data['total_duration'] = $perDay['total_duration'];
        }

        if ($operator)
        {
            $data['operator'] = Operator::query()->find($operator)->username;
        }

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

        Toast::info(__('Успешнно удалили все записи.'));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $calls
     * @return array
     */
    private function getTime(\Illuminate\Database\Eloquent\Collection $calls): array
    {
        $totalCalls = 0;
        $totalDuration = 0;

        foreach ($calls as $call)
        {
            /**
             * @var Call $call
             */
            $totalCalls++;
            $totalDuration += (float) $call->call_duration;
        }

        return [
            'total_calls' => $totalCalls,
            'total_duration' => DateHelper::convertDurationToTime($totalDuration)
        ];
    }
}
