@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Чат с {{ $user->name }}</h2>

            <div class="card">
                <div class="card-body" style="height: 400px; overflow-y: scroll;">
                    @foreach ($messages->reverse() as $message)
                        <div class="d-flex {{ $message->sender_id === Auth::id() ? 'justify-content-end' : '' }} mb-3">
                            <div class="card" style="max-width: 75%;">
                                <div class="card-body p-2">
                                    <p class="mb-0">{{ $message->message }}</p>
                                    <small class="text-muted">{{ $message->created_at->format('d.m.Y H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <form action="{{ route('messages.store', $user) }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="message" class="form-control" placeholder="Напишите сообщение..." required>
                            <button type="submit" class="btn btn-primary">Отправить</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
