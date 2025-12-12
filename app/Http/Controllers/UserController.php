<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        $user->load(['posts' => function ($query) {
            $query->with(['user', 'likers', 'comments' => function ($query) {
                $query->whereNull('parent_id')->with(['user', 'replies' => function($query){
                    $query->with(['user', 'replies']);
                }]);
            }])->latest();
        }]);

        return view('users.show', compact('user'));
    }
}