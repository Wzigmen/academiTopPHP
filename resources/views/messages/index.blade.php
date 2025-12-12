@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Сообщения</h2>

            @if ($friends->count())
                <div class="list-group">
                    @foreach ($friends as $user)
                        <a href="{{ route('messages.show', $user) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}"
                                         alt="{{ $user->name }}" class="rounded-circle me-3" width="50" height="50">
                                @else
                                    <i class="bi bi-person-circle me-3" style="font-size: 50px; color: #6c757d;"></i>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                </div>
                            </div>
                            @if(Auth::user()->hasUnreadMessagesFrom($user))
                                <span class="badge bg-danger rounded-pill"> </span>
                            @endif
                        </a>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $friends->links() }}
                </div>
            @else
                <p>У вас пока нет друзей, чтобы начать переписку.</p>
            @endif
        </div>
    </div>
</div>
@endsection
