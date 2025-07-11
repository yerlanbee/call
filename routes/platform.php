<?php

declare(strict_types=1);

use App\Orchid\Screens\Courses\CourseEditScreen;
use App\Orchid\Screens\Courses\CourseListScreen;
use App\Orchid\Screens\Lessons\LessonEditScreen;
use App\Orchid\Screens\Lessons\LessonListScreen;
use App\Orchid\Screens\Lessons\LessonTimeCodeEditScreen;
use App\Orchid\Screens\Modules\ModuleEditScreen;
use App\Orchid\Screens\Modules\ModuleListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Questions\OptionEditScreen;
use App\Orchid\Screens\Questions\QuestionEditScreen;
use App\Orchid\Screens\Questions\QuestionListScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserImportScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

Route::screen('users/import', UserImportScreen::class)
    ->name('platform.systems.users.import')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.import')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

//Call's
Route::screen('calls', \App\Orchid\Screens\Calls\CallListScreen::class)
    ->name('platform.systems.calls')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
            ->push(__('Звонки'), route('platform.systems.calls')));

Route::screen('calls/import', \App\Orchid\Screens\Calls\CallsImportScreen::class)
    ->name('platform.systems.calls.import')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Звонки'), route('platform.systems.calls')));

//Operator's
Route::screen('operators', \App\Orchid\Screens\Operators\OperatorListScreen::class)
    ->name('platform.systems.operators')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Операторы'), route('platform.systems.operators')));
