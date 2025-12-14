<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Movie;
use App\Models\WatchedMovie;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type', 'movie');

        $results = Movie::where('type', $type)
            ->when($query, function ($q) use ($query) {
                $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($query) . '%'])
                  ->orWhereRaw('LOWER(overview) LIKE ?', ['%' . strtolower($query) . '%']);
            })
            ->simplePaginate(9);

        return view('movies.search', compact('results', 'query', 'type'));
    }

    public function rate(Request $request, Movie $movie)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:10',
        ]);

        WatchedMovie::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'movie_id' => $movie->id,
            ],
            [
                'rating' => $request->rating,
            ]
        );

        return response()->json(['success' => true, 'message' => 'Фильм успешно оценен.']);
    }
}