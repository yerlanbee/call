<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Domains\Auth\Support\ErrorMessages;
use App\Infrastructure\Repositories\Auth\UserRepository;
use App\Infrastructure\Support\Core\CustomException;
use App\Infrastructure\Support\Helpers\Phone;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserPasswordLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Orchid\Platform\Models\User;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Response;

class UserEditScreen extends Screen
{
    /**
     * @var User
     */
    public $user;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(User $user): iterable
    {
        $user->load(['roles']);

        return [
            'user'       => $user,
            'permission' => $user->getStatusPermission(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->user->exists ? 'Редактировать' : 'Создать';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Профиль пользователя и привилегии, включая связанную с ними роль.';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.systems.users',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Impersonate user'))
                ->icon('bg.box-arrow-in-right')
                ->confirm(__('You can revert to your original state by logging out.'))
                ->method('loginAs')
                ->canSee($this->user->exists && $this->user->id !== \request()->user()->id),

            Button::make(__('Remove'))
                ->icon('bs.trash3')
                ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                ->method('remove')
                ->canSee($this->user->exists),

            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [

            Layout::block(UserEditLayout::class)
                ->commands(
                    Button::make(__('Сохранить'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(UserPasswordLayout::class)
                ->commands(
                    Button::make(__('Сохранить'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),
        ];
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(User $user, Request $request)
    {
        $request->validate([
            'user.email' => [
                'nullable',
                Rule::unique(User::class, 'email')->ignore($user),
            ],
            'user.password' => [
                'string'
            ],
            'user.name' => ['nullable', 'string', 'max:60', 'min:2'],
            'user.last_name' => ['nullable', 'string', 'max:60', 'min:2'],
            'user.phone' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
        ]);
        $repository = new UserRepository;
        $data       = $request->all()['user'];
        $exist      = $repository->wherePhone($data['phone'])->first();

        if ($exist)
        {
            new CustomException(ErrorMessages::USER_ALREADY_EXIST, Response::HTTP_UNPROCESSABLE_ENTITY, []);
        }

        $repository->create([
            'email'     => $data['email'] ?? null,
            'password'  => encrypt($data['password']) ?? encrypt('123456Aa'),
            'name'      => $data['name'] ?? Str::random(6),
            'last_name' => $data['last_name'] ?? Str::random(6),
            'phone'     => Phone::normalize($data['phone']),
            'approve_confidential'  => true,
            'email_verified_at'     => now(),
        ]);

        Toast::info(__('Успешно сохранился.'));

        return redirect()->route('platform.systems.users');
    }

    /**
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(User $user)
    {
        $user->delete();

        Toast::info(__('Успешно удалили.'));

        return redirect()->route('platform.systems.users');
    }
}
