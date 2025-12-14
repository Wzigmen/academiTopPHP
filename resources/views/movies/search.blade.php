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
                            <div class="card h-100 d-flex flex-column transition-all duration-200 ease-in-out hover:scale-105 hover:shadow-2xl hover:z-10">
                                @if($result->poster_path)
                                    <img src="https://image.tmdb.org/t/p/w500{{ $result->poster_path }}" class="card-img-top" alt="{{ $result->title }}">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                                        <p class="text-muted">Нет изображения</p>
                                    </div>
                                @endif
                                <div class="card-body flex-grow-1 d-flex flex-column">
                                    <h5 class="card-title">{{ $result->title }}</h5>
                                    <p class="card-text">{{ \Illuminate\Support\Str::limit($result->overview, 100) }}</p>
                                    <p class="card-text"><small class="text-muted">Рейтинг: {{ $result->vote_average }}</small></p>
                                    <div class="mt-auto d-flex justify-content-end">
                                        <button class="btn btn-primary btn-sm rate-movie-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ratingModal"
                                                data-movie-id="{{ $result->id }}"
                                                data-movie-title="{{ $result->title }}">
                                            Добавить для оценки
                                        </button>
                                    </div>
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

<!-- Rating Modal -->
<div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ratingModalLabel">Оценить фильм: <span id="modalMovieTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="ratingForm">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="ratingSelect" class="form-label">Ваша оценка</label>
                        <select class="form-select" id="ratingSelect" name="rating" required>
                            <option value="" disabled selected>Выберите оценку...</option>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить оценку</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ratingModal = document.getElementById('ratingModal');
    const ratingForm = document.getElementById('ratingForm');
    const modalMovieTitle = document.getElementById('modalMovieTitle');

    ratingModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const movieId = button.getAttribute('data-movie-id');
        const movieTitle = button.getAttribute('data-movie-title');

        // Update the modal's content
        modalMovieTitle.textContent = movieTitle;
        ratingForm.action = `/movies/${movieId}/rate`;
    });

    ratingForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(ratingForm);
        const action = ratingForm.action;

        fetch(action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(ratingModal);
                modal.hide();
                ratingForm.reset();
                // Optionally, show a success toast/alert
                alert(data.message);
            } else {
                // Handle validation errors if necessary
                alert('Произошла ошибка. Пожалуйста, попробуйте еще раз.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка. Пожалуйста, попробуйте еще раз.');
        });
    });
});
</script>
@endpush
@endsection

