@extends('templetes.app')
@section('content')
    <div class="container my-5">
        @if (Session::get('success'))
            <div class="alert alert-success mb-4">{{ Session::get('success') }}</div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger mb-4">{{ Session::get('error') }}</div>
        @endif
        <a href="{{ url()->previous() }}" class="btn btn-primary mb-3">
            < Back</a>
                @auth
                    <a href="{{ route('threads.exportPDF', $thread->id) }}" class="btn btn-primary mb-3">
                        Export PDF
                    </a>
                @endauth
                <h1 class="fw-bold fs-3">{{ $thread->title }}</h1>
                @if ($thread->image)
                    <img src="{{ asset('storage/' . $thread->image) }}" class="rounded mb-3" width="300">
                @endif
                <p class="text-muted mt-2" style="word-break: break-word;">
                    {{ $thread->content }}
                </p>
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

                <h5 class="fw-bold">Comment Section</h5>
                @foreach ($thread->comments as $comment)
                    <div class="card mb-3 border-0 shadow-sm rounded-3">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                @if ($comment->user->profile_picture)
                                    <img src="{{ asset('storage/' . $comment->user->profile_picture) }}"
                                        class="rounded-circle border me-3" width="45" height="45"
                                        style="object-fit: cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ $comment->user->name }}"
                                        class="rounded-circle border me-3" width="45" height="45"
                                        style="object-fit: cover">
                                @endif
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-semibold">{{ $comment->user->name }}</h6>
                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="text-muted mb-0 mt-1">{{ $comment->content }}</p>
                                    <div class="d-flex align-items-center gap-3 text-muted small">
                                        <div class="ms-auto d-flex align-items-center gap-2">
                                            <form action="{{ route('like.toggle') }}" method="POST" class="d-inline mx-2">
                                                @csrf
                                                <input type="hidden" name="likeable_id" value="{{ $comment->id }}">
                                                <input type="hidden" name="likeable_type" value="comment">
                                                @php
                                                    $isLiked =
                                                        auth()->check() &&
                                                        $comment
                                                            ->likes()
                                                            ->where('user_id', auth()->id())
                                                            ->exists();
                                                @endphp
                                                <button type="submit"
                                                    class="btn btn-sm {{ $isLiked ? 'btn-primary' : 'btn-outline-primary' }}">
                                                    {{ $isLiked ? '👍 Liked' : '👍 Like' }}
                                                    ({{ $comment->likes()->count() }})
                                                </button>
                                            </form>
                                            @if (auth()->check() && (auth()->user()->id === $comment->user_id || auth()->user()->is_admin))
                                                <form
                                                    action="{{ route('threads.comments.destroy', [$thread->id, $comment->id]) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Yakin hapus komentar ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        🗑 Hapus
                                                    </button>
                                                </form>
                                            @endif
                                            @if (auth()->check() && auth()->id() !== $comment->user_id)
                                                @php
                                                    $hasReported = $comment
                                                        ->reports()
                                                        ->where('user_id', auth()->id())
                                                        ->exists();
                                                @endphp
                                                <form
                                                    action="{{ route('threads.comments.report', [$thread->id, $comment->id]) }}"
                                                    method="POST" class="d-inline mx-2">
                                                    @csrf
                                                    <input type="hidden" name="reportable_id" value="{{ $comment->id }}">
                                                    <input type="hidden" name="reportable_type" value="comment">
                                                    <button type="submit"
                                                        class="btn btn-sm {{ $hasReported ? 'btn-secondary' : 'btn-danger' }}"
                                                        {{ $hasReported ? 'disabled' : '' }}>
                                                        {{ $hasReported ? 'Reported' : 'Report' }}
                                                    </button>
                                                </form>
                                            @endif
                                            {{-- <div class="modal fade" id="reportModal{{ $comment->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form
                                                        action="{{ route('threads.comments.report', [$thread->id, $comment->id]) }}"
                                                        method="POST" class="modal-content">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Laporkan Komentar</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="mb-0"><strong>{{ $comment->content }}</strong></p>
                                                            <div class="mb-0">
                                                                <label for="reason{{ $comment->id }}"
                                                                    class="form-label">Alasan</label>
                                                                <textarea name="reason" id="reason{{ $comment->id }}" class="form-control" rows="3"
                                                                    placeholder="Tulis alasan laporan (mis. spam, ujaran kebencian)" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Kirim
                                                                Laporan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <hr class="my-4">
                <h2 class="fs-5 fw-semibold">Komentar</h2>
                @if (Auth::check())
                    <div class="bg-white p-4 rounded shadow-sm">
                        <!-- Input Komentar -->
                        <div class="d-flex align-items-start gap-3 mb-4">
                            @if (Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                    class="rounded-circle border me-3" width="45" height="45"
                                    style="object-fit: cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
                                    class="rounded-circle border me-3" width="45" height="45"
                                    style="object-fit: cover">
                            @endif
                            <form action="{{ route('threads.comments.store', $thread->id) }}" method="POST"
                                class="flex-grow-1 w-100">
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
                @else
                    <div class="alert alert-info">
                        Silakan <a href="{{ route('login') }}">login</a> untuk menambahkan komentar.
                    </div>
                @endif
                <!-- Komentar Section -->
    </div>
@endsection
