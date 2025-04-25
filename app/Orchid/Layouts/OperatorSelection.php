<?php

namespace App\Orchid\Layouts;

use App\Orchid\Filters\DateFilter;
use App\Orchid\Filters\OperatorFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class OperatorSelection extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [
            OperatorFilter::class,
            DateFilter::class
        ];
    }
}
