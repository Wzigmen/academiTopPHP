@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Друзья {{ $user->name }}</h2>

            @if ($friends->count())
                <div class="list-group">
                    @foreach ($friends as $friend)
                        <a href="{{ route('users.show', $friend) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            @if($friend->avatar)
                                <img src="{{ asset('storage/' . $friend->avatar) }}"
                                     alt="{{ $friend->name }}" class="rounded-circle me-3" width="50" height="50">
                            @else
                                <i class="bi bi-person-circle me-3" style="font-size: 50px; color: #6c757d;"></i>
                            @endif
                            <div>
                                <h6 class="mb-0">{{ $friend->name }}</h6>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $friends->links() }}
                </div>
            @else
                <p>У {{ $user->name }} пока нет друзей.</p>
            @endif
        </div>
    </div>
</div>
@endsection
