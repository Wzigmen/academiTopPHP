@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Поиск</h2>

            <form id="search-form" class="mb-4">
                <div class="input-group">
                    <select id="type-select" name="type" class="form-select">
                        <option value="movie" {{ $type === 'movie' ? 'selected' : '' }}>Фильм</option>
                        <option value="tv" {{ $type === 'tv' ? 'selected' : '' }}>Сериал</option>
                    </select>
                    <input id="query-input" type="text" name="query" class="form-control" placeholder="Поиск..." value="{{ $query }}">
                    <button type="submit" class="btn btn-primary">Поиск</button>
                </div>
            </form>

            <div id="error-container" class="alert alert-danger" style="display: none;"></div>
            <div id="results-container" class="row"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const apiKey = '{{ $apiKey }}';
        const searchForm = document.getElementById('search-form');
        const queryInput = document.getElementById('query-input');
        const typeSelect = document.getElementById('type-select');
        const resultsContainer = document.getElementById('results-container');
        const errorContainer = document.getElementById('error-container');

        async function fetchData() {
            const query = queryInput.value;
            const type = typeSelect.value;
            let endpoint = '';

            if (query) {
                endpoint = `search/${type}`;
            } else {
                endpoint = `${type}/popular`;
            }

            const url = `https://api.themoviedb.org/3/${endpoint}?api_key=${apiKey}&query=${query}&language=ru-RU`;

            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                renderResults(data.results, type);
                errorContainer.style.display = 'none';
            } catch (error) {
                console.error('Fetch error:', error);
                errorContainer.innerText = 'Не удалось загрузить данные. Пожалуйста, проверьте ваше интернет-соединение и попробуйте еще раз.';
                errorContainer.style.display = 'block';
                resultsContainer.innerHTML = '';
            }
        }

        function renderResults(results, type) {
            resultsContainer.innerHTML = '';

            if (results.length === 0) {
                if (queryInput.value) {
                    resultsContainer.innerHTML = '<p>Ничего не найдено по вашему запросу.</p>';
                } else {
                    resultsContainer.innerHTML = '<p>Начните поиск или посмотрите популярные фильмы/сериалы.</p>';
                }
                return;
            }

            results.forEach(result => {
                const title = type === 'movie' ? (result.title || 'N/A') : (result.name || 'N/A');
                const overview = result.overview ? result.overview.substring(0, 100) + '...' : 'Описание отсутствует.';
                const posterPath = result.poster_path ? `https://image.tmdb.org/t/p/w500${result.poster_path}` : 'https://via.placeholder.com/500x750.png?text=No+Image';
                const voteAverage = result.vote_average || 'N/A';

                const card = `
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="${posterPath}" class="card-img-top" alt="${title}">
                            <div class="card-body">
                                <h5 class="card-title">${title}</h5>
                                <p class="card-text">${overview}</p>
                                <p class="card-text"><small class="text-muted">Рейтинг: ${voteAverage}</small></p>
                            </div>
                        </div>
                    </div>
                `;
                resultsContainer.insertAdjacentHTML('beforeend', card);
            });
        }

        searchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            fetchData();
        });

        // Initial fetch on page load
        fetchData();
    });
</script>
@endsection

