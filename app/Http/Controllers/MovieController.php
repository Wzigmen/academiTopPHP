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
        $results = [];
        $error = null;

        if ($query) {
            $endpoint = $type === 'movie' ? 'search/movie' : 'search/tv';

            try {
                $response = Http::withoutVerifying()->get("https://api.themoviedb.org/3/{$endpoint}", [
                    'api_key' => env('TMDB_API_KEY'),
                    'query' => $query,
                    'language' => 'ru-RU',
                ]);

                if ($response->successful()) {
                    $results = $response->json()['results'];
                } else {
                    $error = 'Не удалось получить данные от API.';
                }
            } catch (ConnectionException $e) {
                $error = 'Не удалось подключиться к API. Пожалуйста, проверьте ваше интернет-соединение и попробуйте еще раз.';
            }
        }

        return view('movies.search', compact('results', 'query', 'type', 'error'));
    }
}