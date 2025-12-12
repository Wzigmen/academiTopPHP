<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Movie;

class MovieController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type', 'movie');

        $results = Movie::where('type', $type)
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('overview', 'like', "%{$query}%");
            })
            ->paginate(10);

        return view('movies.search', compact('results', 'query', 'type'));
    }
}