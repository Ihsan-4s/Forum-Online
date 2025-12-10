@extends('templetes.app')
@section('content')
    <div class="container my-5">
        <h1 class="mb-4">Explore Threads</h1>
        <nav class="navbar bg-body-tertiary">
            <form class="d-flex w-100 px-2" role="search" action="" method="GET">
                @csrf
                <input class="form-control me-2" type="text" name="search_threads" placeholder="Search" aria-label="Search">
                <button class="btn btn-primary"  type="submit">Search</button>
            </form>
        </nav>

        <div class="mb-4 p-3 bg-white rounded shadow-sm border-0">
            <h6 class="fw-bold mb-3">
                @if ($tagName)
                    Showing posts tagged: <span class="text-primary">#{{ $tagName }}</span>
                    <a href="{{ route('index') }}" class="btn btn-link btn-sm text-decoration-none">← Clear
                        Filter</a>
                @else
                    Popular Tags
                @endif
            </h6>

            <div>
                @foreach ($popularTags as $popularTag)
                    <a href="{{ route('tags.show', $popularTag->name) }}"
                        class="badge bg-light text-dark border text-decoration-none me-1 mb-1">
                        #{{ $popularTag->name }} <small class="text-muted">({{ $popularTag->threads_count }})</small>
                    </a>
                @endforeach
            </div>
        </div>

        @foreach ($threads as $thread)
            <div class="card mb-3 border-0 shadow-sm p-3 rounded-3">
                <div class="d-flex align-items-start">
                    @if ($thread->user->profile_picture)
                        <img src="{{ asset('storage/' . $thread->user->profile_picture) }}"
                            class="rounded-circle border me-3" width="45" height="45" style="object-fit: cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ $thread->user->name }}"
                            class="rounded-circle border me-3" width="45" height="45" style="object-fit: cover">
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
                                {{ Str::limit(strip_tags($thread->content), 80, '...') }}
                            </p>
                        </div>

                        <div>
                            @foreach ($thread->tags as $tag)
                                <a href="{{ route('tags.show', $tag->name) }}"
                                    class="badge bg-light text-dark border text-decoration-none">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>

                        <div class="d-flex float-end align-items-center">
                            {{-- Tombol Like --}}
                            <form action="{{ route('like.toggle') }}" method="POST" class="d-inline mx-2">
                                @csrf
                                <input type="hidden" name="likeable_id" value="{{ $thread->id }}">
                                <input type="hidden" name="likeable_type" value="thread">
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    👍 Like ({{ $thread->likes()->count() }})
                                </button>
                            </form>

                            {{-- Tombol Komentar --}}
                            <a href="{{ route('threads.show', $thread) }}" class="btn btn-outline-secondary btn-sm mx-2">
                                💬 {{ $thread->comments_count }} Komentar
                            </a>
                            @if (auth()->check() && auth()->id() !== $thread->user_id)
                                <button class="btn btn-sm btn-outline-danger mx-2" data-bs-toggle="modal"
                                    data-bs-target="#reportModal{{ $thread->id }}">
                                    🚩 Laporkan
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Laporkan --}}
            <div class="modal fade" id="reportModal{{ $thread->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('threads.report', $thread) }}" method="POST" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Laporkan Thread</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-2"><strong>{{ $thread->title }}</strong></p>
                            <div class="mb-3">
                                <label for="reason{{ $thread->id }}" class="form-label">Alasan</label>
                                <textarea name="reason" id="reason{{ $thread->id }}" class="form-control" rows="3"
                                    placeholder="Tulis alasan laporan (mis. spam, ujaran kebencian)" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Kirim Laporan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
