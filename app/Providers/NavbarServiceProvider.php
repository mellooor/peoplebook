<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\FriendRequest;
use App\Notification;

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
               $currentUserID = Auth::user()->id;
               $data = [];

               // Get notifications count.
               $data['notification-count'] = Notification::where('user_id', '=', $currentUserID)
                   ->where('is_active', '=', true)->count();

               // Get friend requests count.
               $data['friend-request-count'] = FriendRequest::where('user1_id', '=', $currentUserID)
                   ->orWhere('user2_id', '=', $currentUserID)->count();

               return $view->with('navbarData', $data);
           }
        });
    }
}
