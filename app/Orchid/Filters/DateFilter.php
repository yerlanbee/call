<?php

namespace App\Orchid\Filters;

use App\Traits\DateHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\DateTimer;

class DateFilter extends Filter
{
    public function name(): string
    {
        return 'По дате';
    }

    public function parameters(): array
    {
        return ['date'];
    }

    public function run(Builder $builder): Builder
    {
        $excelFormat = DateHelper::toExcel($this->request->get('date'));

        return $builder->where('date', $excelFormat);
    }

    public function display(): iterable
    {
        return [
            DateTimer::make('date')
                ->title('Дата звонка')
                ->format('d.m.Y')
                ->enableTime(false)
                ->placeholder('Выберите дату'),
        ];
    }
}
