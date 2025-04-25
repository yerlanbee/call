<?php

namespace App\Orchid\Filters;

use App\Models\Operator;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Select;

class OperatorFilter extends Filter
{
    public function name(): string
    {
        return 'Оператор';
    }

    public function parameters(): array
    {
        return ['operator_id'];
    }

    public function run(Builder $builder): Builder
    {
        return $builder->where('operator_id', $this->request->get('operator_id'));
    }

    public function display(): iterable
    {
        return [
            Select::make('operator_id')
                ->fromModel(Operator::class, 'username') // имя поля для отображения
                ->title('Оператор')
                ->empty('Все операторы'),
        ];
    }
}
