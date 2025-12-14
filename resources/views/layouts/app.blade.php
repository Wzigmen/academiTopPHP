<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>academiTOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">academiTOP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Новости</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('friends.index', Auth::user()) }}">
                                Друзья
                                @if(isset($pendingFriendRequestsCount) && $pendingFriendRequestsCount > 0)
                                    <span class="badge bg-primary rounded-pill">{{ $pendingFriendRequestsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('messages.index') }}">
                                Сообщения
                                @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $unreadMessagesCount }}</span>
                                @endif
                            </a>
                        </li>
                        @if(Auth::user()->is_admin)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users.index') }}">Админ-панель</a>
                            </li>
                        @endif
                    @endauth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">О нас</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('search') }}">Поиск</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Вход</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register.view') }}">Регистрация</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if (Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Аватар пользователя" class="rounded-circle" style="width: 30px; height: 30px; object-fit: cover;">
                                @else
                                    <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
                                @endif
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}">Профиль</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">Редактировать профиль</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Выход
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4" style="margin-top: 56px;">
        @yield('content')

        <!-- Toast Container for notifications -->
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <!-- Toasts will be appended here -->
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toastContainer = document.querySelector('.toast-container');
            let shownRequestIds = [];

            function createToast(request) {
                const toastId = `toast-${request.id}`;

                const toastHTML = `
                    <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <strong class="me-auto">Новый запрос в друзья</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            <p>${request.user.name} хочет добавить вас в друзья.</p>
                            <div class="mt-2 pt-2 border-top">
                                <form action="/friends/accept/${request.user.id}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Принять</button>
                                </form>
                                <form action="/friends/remove/${request.user.id}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm">Отклонить</button>
                                </form>
                            </div>
                        </div>
                    </div>
                `;

                toastContainer.insertAdjacentHTML('beforeend', toastHTML);
                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement);

                toastElement.addEventListener('hidden.bs.toast', function () {
                    toastElement.remove();
                });

                toast.show();
            }

            function fetchFriendRequests() {
                fetch('{{ route("notifications.friend-requests") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(requests => {
                    requests.forEach(request => {
                        if (!shownRequestIds.includes(request.id)) {
                            createToast(request);
                            shownRequestIds.push(request.id);
                        }
                    });
                })
                .catch(error => console.error('Error fetching friend requests:', error));
            }

            // Fetch initial requests on page load
            fetchFriendRequests();

            // Poll for new requests every 15 seconds
            setInterval(fetchFriendRequests, 15000);
        });
    </script>
    @endauth
</body>
</html>
