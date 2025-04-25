<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [

            Menu::make('Пользователи')
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.attachment'),

            Menu::make('Операторы')
                ->icon('bs.shield')
                ->route('platform.systems.operators')
                ->permission('platform.systems.attachment'),

            Menu::make('Звонки')
                ->icon('earphones')
                ->route('platform.systems.calls')
                ->permission('platform.systems.attachment'),
        ];
    }
}
