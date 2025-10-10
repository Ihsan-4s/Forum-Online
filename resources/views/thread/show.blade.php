@extends('templetes.app')
@section('content')
    <div class="container my-5">
        <h1 class="fw-bold fs-3">{{ $thread->title }}</h1>
        <p class="text-muted mt-2">{{ $thread->content }}</p>
        <p class="text-secondary small mt-1">
            Dibuat oleh: <strong>{{ $thread->user->name }}</strong> • {{ $thread->created_at->diffForHumans() }}
        </p>
        @if ($thread->tags->isNotEmpty())
            <div class="mt-2">
                @foreach ($thread->tags as $tag)
                    <span class="badge bg-primary me-1">{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif
        <hr class="my-4">
        <h2 class="fs-5 fw-semibold mb-3">Komentar</h2>
        @auth
            <!-- Komentar Section -->
            <div class="bg-white p-4 rounded shadow-sm mt-5">
                <!-- Input Komentar -->
                <div class="d-flex align-items-start gap-3 mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" class="rounded-circle border"
                        width="50" height="50" alt="">
                    <form action="{{ route('comments.store') }}" method="POST" class="flex-grow-1 w-100">
                        @csrf
                        <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                        <div class="mb-2">
                            <textarea name="content" class="form-control" rows="3" placeholder="Tulis komentar Anda..." required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endauth
        <h5 class="fw-bold my-4">Comment Section</h5>
        @foreach ($thread->comments as $comment)
        <div class="card mb-3 border-0 shadow-sm rounded-3">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ $comment->user->name }}" class="rounded-circle"
                            width="40" height="40" alt="">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">{{ $comment->user->name }}</h6>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="text-muted mb-2 mt-1">{{ $comment->content }}</p>
                        <div class="d-flex align-items-center gap-3 text-muted small">
                            <button class="btn btn-link p-0 text-decoration-none text-secondary"><i
                                    class="fa-regular fa-comment"></i> Balas</button>
                            <button class="btn btn-link p-0 text-decoration-none text-secondary"><i
                                    class="fa-regular fa-share-from-square"></i> Bagikan</button>
                            <div class="ms-auto d-flex align-items-center gap-2">
                                <i class="fa-regular fa-thumbs-up"></i> 0
                                <i class="fa-regular fa-thumbs-down ms-2"></i> 0
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection
