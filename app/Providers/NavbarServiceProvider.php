<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\FriendRequest;

class NavbarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function($view) {
           if (Auth::check()) {
               $data = [];

               // Get notifications count.

               // Get friend requests count.
               $data['friend-request-count'] = FriendRequest::count();

               return $view->with('navbarData', $data);
           }
        });
    }
}
