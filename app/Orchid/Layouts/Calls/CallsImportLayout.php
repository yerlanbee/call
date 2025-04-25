<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Calls;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class CallsImportLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('file')
                ->type('file')
                ->required()
                ->title('Файл CSV')
                ->placeholder(__('Файл CSV')),
        ];
    }
}
