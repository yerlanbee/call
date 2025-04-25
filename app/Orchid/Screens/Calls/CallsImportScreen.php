<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Calls;

use App\Imports\CallsCsvImport;
use App\Infrastructure\Imports\UsersCsvImport;
use App\Orchid\Layouts\Calls\CallsImportLayout;
use App\Orchid\Layouts\User\UsersImportLayout;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CallsImportScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Импорт звонков из csv файла';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Предупреждение! Тип формата и колонки в файла должны сохранятся в строгом порядке!';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.systems.attachment',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [

            Layout::block(CallsImportLayout::class)
                ->commands(
                    Button::make(__('Сохранить'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->method('save')
                )
        ];
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        Excel::import(new CallsCsvImport, request()->file('file'));

        Toast::info(__('Успешно сохранился.'));

        return redirect()->route('platform.systems.calls');
    }
}
