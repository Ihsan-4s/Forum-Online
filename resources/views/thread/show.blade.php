@extends('templetes.app')
@section('content')
    <div class="container my-5">
        <h1 class="fw-bold fs-3">{{ $thread->title }}</h1>
        <p class="text-muted mt-2" style="word-wrap: break-word; overflow-wrap: break-word; white-space: pre-wrap;">
            {{ $thread->content }}
        </p>
        @if ($thread->image)
            <img src="{{ asset('storage/' . $thread->image) }}" class="rounded mb-2" width="200" alt="Thread Image">
        @endif
        <p class="text-secondary small mt-1">
            Dibuat oleh: <strong>{{ $thread->user->name }}</strong> • {{ $thread->created_at->diffForHumans() }}
        </p>
        @if ($thread->tags->isNotEmpty())
            <div class="mt-2">
                @foreach ($thread->tags as $tag)
                    <span class="badge badge-secondary me-1">{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif

        <h5 class="fw-bold my-4">Comment Section</h5>
        @foreach ($thread->comments as $comment)
            <div class="card mb-3 border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        @if ($comment->user->profile_picture)
                            <img src="{{ asset('storage/' . $comment->user->profile_picture) }}"
                                class="rounded-circle border me-3" width="45" height="45" style="object-fit: cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ $comment->user->name }}"
                                class="rounded-circle border me-3" width="45" height="45" style="object-fit: cover">
                        @endif
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
                                    <form action="{{ route('like.toggle') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="likeable_id" value="{{ $comment->id }}">
                                        <input type="hidden" name="likeable_type" value="comment">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            👍 Like ({{ $comment->likes()->count() }})
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <hr class="my-4">
        <h2 class="fs-5 fw-semibold mb-3">Komentar</h2>
        @auth
            <!-- Komentar Section -->
            <div class="bg-white p-4 rounded shadow-sm mt-5">
                <!-- Input Komentar -->
                <div class="d-flex align-items-start gap-3 mb-4">
                    @if (Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="rounded-circle border me-3"
                            width="45" height="45" style="object-fit: cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
                            class="rounded-circle border me-3" width="45" height="45" style="object-fit: cover">
                    @endif
                    <form action="{{ route('threads.comments.store', $thread->id) }}" method="POST" class="flex-grow-1 w-100">
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
    </div>
@endsection
