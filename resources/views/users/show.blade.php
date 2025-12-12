@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-3">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}"
                         alt="{{ $user->name }}" class="rounded-circle me-3" width="100" height="100">
                @else
                    <i class="bi bi-person-circle me-3" style="font-size: 100px; color: #6c757d;"></i>
                @endif
                <div>
                    <h2 class="mb-0">{{ $user->name }}</h2>
                    @auth
                        @if(Auth::user()->id !== $user->id)
                            <div class="mt-2">
                                @if (Auth::user()->isFriendsWith($user))
                                    <form action="{{ route('friends.remove', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Удалить из друзей</button>
                                    </form>
                                @elseif (Auth::user()->hasSentFriendRequestTo($user))
                                    <button type="button" class="btn btn-secondary" disabled>Запрос отправлен</button>
                                @elseif (Auth::user()->hasPendingFriendRequestFrom($user))
                                    <form action="{{ route('friends.accept', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Принять запрос</button>
                                    </form>
                                @else
                                    <form action="{{ route('friends.send', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Добавить в друзья</button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if (Auth::user()->id === $user->id && Auth::user()->friendRequests->count())
                <div class="my-4">
                    <h4>Запросы в друзья</h4>
                    @foreach (Auth::user()->friendRequests as $request)
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <a href="{{ route('users.show', $request->user) }}" class="text-decoration-none text-dark">
                                    @if($request->user->avatar)
                                        <img src="{{ asset('storage/' . $request->user->avatar) }}"
                                             alt="{{ $request->user->name }}" class="rounded-circle me-2" width="40" height="40">
                                    @else
                                        <i class="bi bi-person-circle me-2" style="font-size: 40px; color: #6c757d;"></i>
                                    @endif
                                    {{ $request->user->name }}
                                </a>
                            </div>
                            <div>
                                <form action="{{ route('friends.accept', $request->user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Принять</button>
                                </form>
                                <form action="{{ route('friends.remove', $request->user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Отклонить</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($user->friends->count())
                <div class="my-4">
                    <h4>Друзья ({{$user->friends->count()}})</h4>
                    <a href="{{ route('friends.index', $user) }}" class="btn btn-primary">Посмотреть</a>
                </div>
            @endif

            <hr>

            <h3>Посты пользователя</h3>

            @if($user->posts->count())
                @foreach ($user->posts as $post)
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                @if($post->user->avatar)
                                    <img src="{{ asset('storage/' . $post->user->avatar) }}"
                                         alt="{{ $post->user->name }}" class="rounded-circle me-3" width="50" height="50">
                                @else
                                    <i class="bi bi-person-circle me-3" style="font-size: 50px; color: #6c757d;"></i>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $post->user->name }}</h6>
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
                        <p class="card-text">У этого пользователя пока нет постов.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
