<div class="d-flex justify-content-between align-items-start mb-2" style="margin-left: {{ $level * 20 }}px">
    <div>
        <small class="fw-bold">
            <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none text-dark">{{ $comment->user->name }}</a>:
        </small>
        <small>{{ $comment->body }}</small>
        <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
        @auth
            <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#reply-form-{{ $comment->id }}" aria-expanded="false" aria-controls="reply-form-{{ $comment->id }}">
                Ответить
            </button>
        @endauth
    </div>
    @can('delete', $comment)
        <form action="{{ route('comments.destroy', $comment) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверены?')">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    @endcan
</div>

@auth
    <div class="collapse" id="reply-form-{{ $comment->id }}" style="margin-left: {{ $level * 20 }}px">
        <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-3">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <div class="mb-2">
                <textarea name="body" class="form-control" rows="2" placeholder="Добавить ответ" required></textarea>
            </div>
            <button type="submit" class="btn btn-sm btn-outline-primary">Отправить</button>
        </form>
    </div>
@endauth

@if ($comment->replies->count() > 0)
    @foreach ($comment->replies as $reply)
        @include('posts._comment', ['comment' => $reply, 'level' => $level + 1])
    @endforeach
@endif
