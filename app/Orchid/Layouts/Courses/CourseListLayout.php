<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Courses;

use App\Infrastructure\Models\Course\Course;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CourseListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'courses';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('name', 'Название')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Course $course) => $course->title),

            TD::make('name', 'Описание')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Course $course) => $course->description),

            TD::make('name', 'Опубликован')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Course $course) => $course->published),

            TD::make('name', 'Цвет')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Course $course) => $course->color),

            TD::make('created_at', 'Создан')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Course $course) => $course->created_at),

            TD::make('created_at', 'Обновлен')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Course $course) => $course->updated_at),

            TD::make('Действия')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Course $course) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([

                        Link::make(__('Редактировать'))
                            ->route('platform.systems.courses.edit', $course->id)
                            ->icon('bs.pencil'),

                        Button::make(__('Удалить'))
                            ->icon('bs.trash3')
                            ->confirm('Вы точно хотите удалить?')
                            ->method('remove', [
                                'id' => $course->id,
                            ]),
                    ])),
        ];
    }
}
