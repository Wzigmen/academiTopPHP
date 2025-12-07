<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $post->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        return back()->with('status', 'Комментарий успешно добавлен!');
    }
}
