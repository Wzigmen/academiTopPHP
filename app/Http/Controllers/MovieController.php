<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Client\ConnectionException;

class MovieController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type', 'movie');
        $apiKey = env('TMDB_API_KEY');

        return view('movies.search', compact('query', 'type', 'apiKey'));
    }
}