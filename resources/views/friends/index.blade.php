@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Search Form -->
            <div class="mb-4">
                <h4>Найти пользователей</h4>
                <form action="{{ route('friends.index', Auth::user()) }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="query" class="form-control" placeholder="Введите имя пользователя..." value="{{ $query ?? '' }}">
                        <button type="submit" class="btn btn-primary">Поиск</button>
                    </div>
                </form>
            </div>

            <hr>

            @if($isSearch)
                <h2>Результаты поиска</h2>
            @else
                <h2>Друзья {{ $user->name }}</h2>
            @endif

            @if ($users->count())
                <div class="list-group">
                    @foreach ($users as $item)
                        <div class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
                            <a href="{{ route('users.show', $item) }}" class="text-decoration-none text-dark d-flex align-items-center">
                                @if($item->avatar)
                                    <img src="{{ asset('storage/' . $item->avatar) }}"
                                         alt="{{ $item->name }}" class="rounded-circle me-3" width="50" height="50">
                                @else
                                    <i class="bi bi-person-circle me-3" style="font-size: 50px; color: #6c757d;"></i>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $item->name }}</h6>
                                </div>
                            </a>

                            @if($isSearch && Auth::user()->id !== $item->id)
                                <div class="friend-actions">
                                    @if (Auth::user()->isFriendsWith($item))
                                        <form action="{{ route('friends.remove', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Удалить из друзей</button>
                                        </form>
                                    @elseif (Auth::user()->hasSentFriendRequestTo($item))
                                        <button type="button" class="btn btn-sm btn-secondary" disabled>Запрос отправлен</button>
                                    @elseif (Auth::user()->hasPendingFriendRequestFrom($item))
                                        <form action="{{ route('friends.accept', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Принять запрос</button>
                                        </form>
                                    @else
                                        <form action="{{ route('friends.send', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">Добавить в друзья</button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $users->links() }}
                </div>
            @else
                @if($isSearch)
                    <p>По вашему запросу ничего не найдено.</p>
                @else
                    <p>У {{ $user->name }} пока нет друзей.</p>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
