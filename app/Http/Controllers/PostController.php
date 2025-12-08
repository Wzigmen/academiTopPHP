<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user', 'comments.user', 'likers')->latest()->simplePaginate(5);
        return view('dashboard', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $request->user()->posts()->create($request->only('title', 'body'));

        return redirect()->route('dashboard')->with('status', 'Пост успешно создан!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post->update($request->only('title', 'body'));

        return redirect()->route('dashboard')->with('status', 'Пост успешно обновлен!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('dashboard')->with('status', 'Пост успешно удален!');
    }

    /**
     * Like a post.
     */
    public function like(Post $post)
    {
        auth()->user()->likes()->attach($post->id);
        return back();
    }

    /**
     * Unlike a post.
     */
    public function unlike(Post $post)
    {
        auth()->user()->likes()->detach($post->id);
        return back();
    }
}
