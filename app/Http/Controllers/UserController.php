<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        $user->loadCount(['watchedMovies', 'friendsOfMine', 'friendOf']);
        $user->load(['posts' => function ($query) {
            $query->with(['user', 'likers', 'comments' => function ($query) {
                $query->whereNull('parent_id')->with(['user', 'replies' => function($query){
                    $query->with(['user', 'replies']);
                }]);
            }])->latest();
        }]);

        return view('users.show', compact('user'));
    }

    public function watched(User $user)
    {
        $watchedMovies = $user->ratedMovies()->paginate(9);

        return view('users.watched', compact('user', 'watchedMovies'));
    }
}