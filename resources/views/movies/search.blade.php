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

            @if (isset($error))
                <div class="alert alert-danger">{{ $error }}</div>
            @endif

            @if (!empty($results))
                <div class="row">
                    @foreach ($results as $result)
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="https://image.tmdb.org/t/p/w500{{ $result['poster_path'] }}" class="card-img-top" alt="{{ $type === 'movie' ? $result['title'] : $result['name'] }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $type === 'movie' ? $result['title'] : $result['name'] }}</h5>
                                    <p class="card-text">{{ \Illuminate\Support\Str::limit($result['overview'], 100) }}</p>
                                    <p class="card-text"><small class="text-muted">Рейтинг: {{ $result['vote_average'] }}</small></p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif(request('query'))
                <p>Ничего не найдено.</p>
            @endif
        </div>
    </div>
</div>
@endsection
