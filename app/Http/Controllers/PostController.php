<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['user', 'likers', 'comments' => function ($query) {
            $query->whereNull('parent_id')->with(['user', 'replies' => function($query){
                $query->with(['user', 'replies']);
            }]);
        }])->latest()->simplePaginate(5);
        return view('dashboard', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:png|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $validatedData['image_path'] = $path;
        }

        $request->user()->posts()->create([
            'title' => $validatedData['title'],
            'body' => $validatedData['body'],
            'image_path' => $validatedData['image_path'] ?? null,
        ]);

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

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:png|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $path = $request->file('image')->store('posts', 'public');
            $validatedData['image_path'] = $path;
        }

        $post->update($validatedData);

        return redirect()->route('dashboard')->with('status', 'Пост успешно обновлен!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

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
