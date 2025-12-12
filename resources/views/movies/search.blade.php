@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Поиск</h2>

            <form action="{{ route('search') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <select name="type" class="form-select">
                        <option value="movie" {{ $type === 'movie' ? 'selected' : '' }}>Фильм</option>
                        <option value="tv" {{ $type === 'tv' ? 'selected' : '' }}>Сериал</option>
                    </select>
                    <input type="text" name="query" class="form-control" placeholder="Поиск..." value="{{ $query }}">
                    <button type="submit" class="btn btn-primary">Поиск</button>
                </div>
            </form>

            @if ($results->count())
                <div class="row">
                    @foreach ($results as $result)
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                @if($result->poster_path)
                                    <img src="https://image.tmdb.org/t/p/w500{{ $result->poster_path }}" class="card-img-top" alt="{{ $result->title }}">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                                        <p class="text-muted">Нет изображения</p>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $result->title }}</h5>
                                    <p class="card-text">{{ \Illuminate\Support\Str::limit($result->overview, 100) }}</p>
                                    <p class="card-text"><small class="text-muted">Рейтинг: {{ $result->vote_average }}</small></p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $results->appends(request()->query())->links() }}
            @else
                <p>Ничего не найдено.</p>
            @endif
        </div>
    </div>
</div>
@endsection

