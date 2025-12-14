<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Message;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $unreadMessagesCount = Message::where('receiver_id', $user->id)->whereNull('read_at')->count();
                $pendingFriendRequestsCount = $user->friendRequests()->count();
                $view->with('unreadMessagesCount', $unreadMessagesCount)
                     ->with('pendingFriendRequestsCount', $pendingFriendRequestsCount);
            }
        });
    }
}
