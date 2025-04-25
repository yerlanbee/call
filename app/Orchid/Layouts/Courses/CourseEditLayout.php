<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Courses;

use App\Infrastructure\Models\Course\Module;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class CourseEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('course.title')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Краткое описание')
                ->placeholder(__('Краткое описание')),

            Input::make('course.description')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Описание')
                ->placeholder(__('Описание')),

            Input::make('course.color')
                ->type('text')
                ->max(255)
                ->title('Код цвета на заднем фоне')
                ->placeholder(__('Код цвета на заднем фоне')),

            Input::make('course.price')
                ->type('text')
                ->required()
                ->title('Цена')
                ->placeholder(__('Цена')),

            Input::make('course.image_path')
                ->type('text')
                ->title('Прикрепленный файл url')
                ->placeholder(__('Прикрепленный файл url')),

            Select::make('course.module_id')
                ->fromModel(Module::class, 'name')
                ->empty('Не выбрано')
                ->title('Модуль')
                ->help('Выберите модуль из списка'),

            CheckBox::make('course.published')
                ->title('Активный')
                ->sendTrueOrFalse(),

//            Input::make('course.image')
//                ->type('file')
//                ->title('file')
//                ->help('Допустимые форматы: JPG, PNG, GIF'),
        ];
    }
}
