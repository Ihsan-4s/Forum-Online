@extends('templetes.app')

@section('content')
    <div class="container-fluid py-4 px-5 bg-light">
        @if (Session::get('success'))
            <div class="alert alert-success mb-4">{{ Session::get('success') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body d-flex align-items-center">
                        @auth
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" class="rounded-circle me-3"
                                width="50">
                            <input type="text" class="form-control rounded-pill" placeholder="Tulis sesuatu...">
                        @else
                            <img src="https://ui-avatars.com/api/?name=Guest" class="rounded-circle me-3" width="50">
                            <input type="text" class="form-control rounded-pill" placeholder="Tulis sesuatu...">
                        @endauth
                    </div>
                </div>

                @foreach ($threads as $thread)
                    <div class="card mb-3 border-0 shadow-sm p-3 rounded-3">
                        <div class="d-flex align-items-start">
                            <img src="https://ui-avatars.com/api/?name={{ $thread->user->name }}"
                                class="rounded-circle me-3" width="45" height="45" alt="{{ $thread->user->name }}">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-semibold mb-0">{{ $thread->user->name }}</h6>
                                        <small class="text-muted">{{ $thread->created_at->diffForHumans() }}</small>
                                    </div>
                                    <span
                                        class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">SELESAI</span>
                                </div>

                                <div class="mt-2">
                                    <h5 class="fw-bold mb-1 text-dark">{{ $thread->title }}</h5>
                                    @if ($thread->image)
                                        <img src="{{ asset('storage/' . $thread->image) }}" class="rounded mb-2"
                                            width="200">
                                    @endif
                                    <p class="text-muted mb-2" style="font-size: 0.95rem;">
                                        {{ Str::limit($thread->content, 180, '...') }}
                                    </p>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="badge bg-light text-dark border">#{{ $thread->tag }}</span>
                                    <div class="text-muted small">
                                        <a href="{{ route('threads.show', $thread) }}"
                                            class="btn btn-outline-secondary btn-sm me-2">
                                            {{ $thread->comments_count }} Pembahasan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body text-center">
                        @auth

                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" class="rounded-circle mb-3"
                                width="80">
                            <h6 class="fw-bold mb-0">{{ Auth::user()->name }}</h6>
                            <p class="text-muted small">{{ '@' . Str::slug(Auth::user()->name, '_') }}</p>
                            <a href="#" class="btn btn-primary btn-sm w-100 rounded-pill mb-2">My Profile</a>
                            <a class="btn btn-secondary btn-sm w-100 rounded-pill " href="{{ route('logout') }}">Logout</a>
                        @else
                            <img src="https://ui-avatars.com/api/?name=Guest" class="rounded-circle mb-3" width="80">
                            <h6 class="fw-bold mb-0">Guest User</h6>
                            <p class="text-muted small">@anonymous</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill">Login</a>
                        @endauth
                    </div>
                    <div class="card-footer bg-white text-center small text-muted">
                        <span class="mx-2">250 Posts</span>•<span class="mx-2">2022 Followers</span>•<span
                            class="mx-2">590 Following</span>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Your Shortcuts</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-palette me-2 text-primary"></i>Art & Drawing</li>
                            <li class="mb-2"><i class="bi bi-behance me-2 text-primary"></i>Behance Creative</li>
                            <li><i class="bi bi-controller me-2 text-primary"></i>Game Community</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
