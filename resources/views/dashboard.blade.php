@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Лента новостей</h2>
                @if(Auth::user()->is_admin)
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">Создать пост</a>
                @endif
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if($posts->count())
                @foreach ($posts as $post)
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none text-dark">
                                @if($post->user->avatar)
                                    <img src="{{ asset('storage/' . $post->user->avatar) }}"
                                         alt="{{ $post->user->name }}" class="rounded-circle me-3" width="50" height="50">
                                @else
                                    <i class="bi bi-person-circle me-3" style="font-size: 50px; color: #6c757d;"></i>
                                @endif
                                </a>
                                <div>
                                    <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none text-dark"><h6 class="mb-0">{{ $post->user->name }}</h6></a>
                                </div>
                            </div>
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ $post->body }}</p>
                            @if ($post->image_path)
                                <img src="{{ asset('storage/' . $post->image_path) }}" alt="Изображение поста" class="img-fluid rounded mt-3">
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <small class="text-muted">{{ $post->created_at->format('d.m.Y H:i') }}</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    @auth
                                        @if ($post->likedBy(auth()->user()))
                                            <form action="{{ route('posts.unlike', $post) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger me-2">
                                                    <i class="bi bi-heart-fill"></i> ({{ $post->likers->count() }})
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('posts.like', $post) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger me-2">
                                                    <i class="bi bi-heart"></i> ({{ $post->likers->count() }})
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                    @guest
                                        <button type="button" class="btn btn-sm btn-outline-danger me-2" disabled>
                                            <i class="bi bi-heart"></i> ({{ $post->likers->count() }})
                                        </button>
                                    @endguest
                                    @if (auth()->id() == $post->user_id)
                                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-secondary me-2">Редактировать</a>
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <h6>Комментарии ({{ $post->comments()->count() }})</h6>
                            @foreach ($post->comments as $comment)
                                @include('posts._comment', ['comment' => $comment, 'level' => 0, 'post' => $post])
                            @endforeach
                            @auth
                                <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-3">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea name="body" class="form-control" rows="2" placeholder="Добавить комментарий" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Комментировать</button>
                                </form>
                            @endauth
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">Постов пока нет. Создайте первый!</p>
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
