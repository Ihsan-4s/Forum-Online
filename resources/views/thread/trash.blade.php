@extends('templetes.app')
@section('content')
    <div class="container my-5">
        <h1 class="mb-3">Restore your threads</h1>
        @forelse ($threads as $thread)
            <div class="card mb-3 border-0 shadow-sm p-3 rounded-3">
                <div class="d-flex align-items-start">
                    @if (Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="rounded-circle me-3"
                            width="45" height="45" alt="{{ Auth::user()->name }}">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" class="rounded-circle me-3"
                            width="45" height="45" alt="{{ Auth::user()->name }}">
                    @endif
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-semibold mb-0">{{ $thread->user->name }}</h6>
                                <small class="text-muted">{{ $thread->created_at->diffForHumans() }}</small>
                            </div>
                        </div>

                        <div class="mt-2">
                            <h5 class="fw-bold mb-1 text-dark">{{ $thread->title }}</h5>
                            @if ($thread->image)
                                <img src="{{ asset('storage/' . $thread->image) }}" class="rounded mb-2" width="200">
                            @endif
                            <p class="text-muted mb-2" style="font-size: 0.95rem;">
                                {{ Str::limit($thread->content, 80, '...') }}
                            </p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div>
                                @foreach ($thread->tags as $tag)
                                    <a href="{{ route('tags.show', $tag->name) }}"
                                        class="badge bg-light text-dark border text-decoration-none">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>

                            <div class="text-muted small">
                                <form action="{{ route('threads.restore', $thread) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin Restore')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-success btn-sm">
                                        Restore
                                    </button>
                                </form>
                                <form action="{{ route('threads.deletePermanent', $thread) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin hapus thread ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">You have not created any threads yet.</p>
        @endforelse
    </div>
@endsection
