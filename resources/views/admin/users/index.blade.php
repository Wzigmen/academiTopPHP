@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2>Управление пользователями</h2>

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

            <div class="card mt-4">
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                @php
                                    $createSortLink = function($column) use ($sortColumn, $sortDirection) {
                                        $direction = ($column === $sortColumn && $sortDirection === 'asc') ? 'desc' : 'asc';
                                        return route('admin.users.index', ['sort' => $column, 'direction' => $direction]);
                                    };
                                @endphp
                                <th scope="col">
                                    <a href="{{ $createSortLink('id') }}">
                                        #
                                        @if($sortColumn === 'id')
                                            <i class="bi bi-caret-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col">
                                    <a href="{{ $createSortLink('name') }}">
                                        Имя
                                        @if($sortColumn === 'name')
                                            <i class="bi bi-caret-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col">
                                    <a href="{{ $createSortLink('email') }}">
                                        Email
                                        @if($sortColumn === 'email')
                                            <i class="bi bi-caret-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col">
                                    <a href="{{ $createSortLink('is_admin') }}">
                                        Админ
                                        @if($sortColumn === 'is_admin')
                                            <i class="bi bi-caret-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col">
                                    <a href="{{ $createSortLink('created_at') }}">
                                        Дата регистрации
                                        @if($sortColumn === 'created_at')
                                            <i class="bi bi-caret-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <th scope="row">{{ $user->id }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->is_admin ? 'Да' : 'Нет' }}</td>
                                    <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        @if($user->id === 1)
                                            <span class="text-success fw-bold">Супер-админ</span>
                                        @elseif(Auth::id() === $user->id)
                                            <span class="text-muted">Это вы</span>
                                        @else
                                            <form action="{{ route('admin.users.toggleAdmin', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @if ($user->is_admin)
                                                    <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Вы уверены, что хотите отозвать права администратора?')">Разжаловать</button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Вы уверены, что хотите предоставить права администратора?')">Сделать админом</button>
                                                @endif
                                            </form>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline ms-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя? Это действие необратимо.')">Удалить</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
