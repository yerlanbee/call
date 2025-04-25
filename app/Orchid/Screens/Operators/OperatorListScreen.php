<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Operators;

use App\Models\Operator;
use App\Orchid\Layouts\Courses\CourseListLayout;
use App\Orchid\Layouts\Operators\OperatorListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
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
        return [
            'operators' => Operator::query()
                ->orderBy('id', 'desc')
                ->paginate(15),
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
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
//            Link::make(__('Добавить'))
//                ->icon('bs.plus-circle')
//                ->route('platform.systems.courses.create'),
        ];
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
