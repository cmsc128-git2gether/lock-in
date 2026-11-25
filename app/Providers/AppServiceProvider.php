<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Password::defaults(function () {
            return Password::min(8)  //minimum 8 characters
                ->numbers()  //al 1 number
                ->symbols(); //al 1 special character
        });

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                // group lists
                // owned by user
                $ownedGroups = $user->groups()->where('owner_id', $user->id)->orderBy('name')->get();
                // user is member
                $sharedGroups = $user->groups()->where('owner_id', '!=', $user->id)->with('owner')->orderBy('name')->get();

                $view->with(compact('ownedGroups', 'sharedGroups'));
            }
        });

        View::composer('layouts.sidebar', function ($view) {
            $user = auth()->user();

            if (! $user) {
                return;
            }

            $groups = $user->groups()->with('owner')->orderBy('groups.name')->get();

            $view->with([
                'ownedGroups'  => $groups->where('owner_id', $user->id)->values(),
                'sharedGroups' => $groups->where('owner_id', '!=', $user->id)->values(),
                'trashCount'   => Task::onlyTrashed()->where('user_id', $user->id)->count(),
            ]);
        });
    }
}
