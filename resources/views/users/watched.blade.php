@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Фильмы, которые смотрел(а) {{ $user->name }}</h2>

            @if ($watchedMovies->count())
                <div class="row">
                    @foreach ($watchedMovies as $movie)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 d-flex flex-column">
                                @if($movie->poster_path)
                                    <img src="https://image.tmdb.org/t/p/w500{{ $movie->poster_path }}" class="card-img-top" alt="{{ $movie->title }}">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                                        <p class="text-muted">Нет изображения</p>
                                    </div>
                                @endif
                                <div class="card-body flex-grow-1 d-flex flex-column">
                                    <h5 class="card-title">{{ $movie->title }}</h5>
                                    <p class="card-text">{{ \Illuminate\Support\Str::limit($movie->overview, 100) }}</p>
                                    <div class="mt-auto">
                                        <p class="card-text">
                                            <small class="text-muted">Общий рейтинг: {{ $movie->vote_average }}</small>
                                        </p>
                                        <p class="card-text">
                                            <strong>Ваша оценка: {{ $movie->pivot->rating }}/10</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $watchedMovies->links() }}
            @else
                <p>{{ $user->name }} еще не оценивал(а) фильмы.</p>
            @endif
        </div>
    </div>
</div>
@endsection
