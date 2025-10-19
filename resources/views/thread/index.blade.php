@extends('templetes.app')

@section('content')
    <div class="container-fluid py-4 px-5 bg-light">
        @if (Session::get('success'))
            <div class="alert alert-success mb-4">{{ Session::get('success') }}</div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger mb-4">{{ Session::get('error') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body d-flex align-items-center gap-4">
                        @auth
                            {{-- Profile Picture --}}
                            <div class="position-relative">
                                @if (Auth::user()->profile_picture)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="profile"
                                        class="rounded-circle border" width="120" height="120" style="object-fit: cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" alt="profile"
                                        class="rounded-circle border" width="120" height="120" style="object-fit: cover">
                                @endif
                            </div>
                            {{-- User Info --}}
                            <div>
                                <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                                <p class="text-muted small mb-1">{{ '@' . Str::slug(Auth::user()->name, '_') }}</p>
                                <p class="text-muted small">Joined {{ Auth::user()->created_at->diffForHumans() }}</p>
                            </div>
                        @else
                            <p>Silahkan Login untuk explore PointSale</p>
                        @endauth
                    </div>
                </div>

                {{-- Filter by Tag --}}
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
                                #{{ $popularTag->name }} <small
                                    class="text-muted">({{ $popularTag->threads_count }})</small>
                            </a>
                        @endforeach
                    </div>
                </div>

                <hr>

                {{-- Threads List --}}
                @foreach ($threads as $thread)
                    <div class="card mb-3 border-0 shadow-sm p-3 rounded-3">
                        <div class="d-flex align-items-start">
                            @if ($thread->user->profile_picture)
                                <img src="{{ asset('storage/' . $thread->user->profile_picture) }}"
                                    class="rounded-circle border me-3" width="45" height="45"
                                    style="object-fit: cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ $thread->user->name }}"
                                    class="rounded-circle border me-3" width="45" height="45"
                                    style="object-fit: cover">
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
                                        <img src="{{ asset('storage/' . $thread->image) }}" class="rounded mb-2"
                                            width="200">
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
                                <div class="d-flex float-end">
                                    <form action="{{ route('like.toggle') }}" method="POST" class="d-inline mx-3">
                                        @csrf
                                        <input type="hidden" name="likeable_id" value="{{ $thread->id }}">
                                        <input type="hidden" name="likeable_type" value="thread">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            👍 Like ({{ $thread->likes()->count() }})
                                        </button>
                                    </form>

                                    <a href="{{ route('threads.show', $thread) }}"
                                        class="btn btn-outline-secondary btn-sm float-end">
                                        {{ $thread->comments_count }} Komentar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body text-center">
                        @auth
                            @if (Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="rounded-circle mb-3"
                                    width="80" height="80" style="object-fit: cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
                                    class="rounded-circle mb-3" width="80">
                            @endif
                            <h6 class="fw-bold mb-0">{{ Auth::user()->name }}</h6>
                            <p class="text-muted small">{{ '@' . Str::slug(Auth::user()->name, '_') }}</p>
                            <a href="#" class="btn btn-primary btn-sm w-100 rounded-pill mb-2">My Profile</a>
                            <a class="btn btn-secondary btn-sm w-100 rounded-pill" href="{{ route('logout') }}">Logout</a>
                        @else
                            <img src="https://ui-avatars.com/api/?name=Guest" class="rounded-circle mb-3" width="80">
                            <h6 class="fw-bold mb-0">Guest User</h6>
                            <p class="text-muted small">@anonymous</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill">Login</a>
                        @endauth
                    </div>

                </div>

                {{-- Notifications --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Notifikasi</h6>
                        @auth
                            @if (Auth::user()->unreadNotifications->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach (Auth::user()->unreadNotifications as $notification)
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                @if (isset($notification->data['commented_by']))
                                                    <div>
                                                        <strong>{{ $notification->data['commented_by'] }}</strong> mengomentari
                                                        thread
                                                        <a
                                                            href="{{ route('threads.show', $notification->data['thread_id']) }}">
                                                            "{{ Str::limit($notification->data['thread_title'], 30) }}"
                                                        </a>
                                                    </div>
                                                @elseif(isset($notification->data['liked_by']))
                                                    <div>
                                                        <strong>{{ $notification->data['liked_by'] }}</strong> menyukai thread
                                                        <a
                                                            href="{{ route('threads.show', $notification->data['thread_id']) }}">
                                                            "{{ Str::limit($notification->data['thread_title'], 30) }}"
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                            <span
                                                class="badge bg-primary rounded-pill">{{ $notification->created_at->diffForHumans() }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted small mb-0">Belum ada notifikasi</p>
                            @endif
                        @else
                            <p class="text-muted small mb-0">Login untuk melihat notifikasi</p>
                        @endauth
                    </div>
                </div>

                {{-- <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Your Shortcuts</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-palette me-2 text-primary"></i>Art & Drawing</li>
                            <li class="mb-2"><i class="bi bi-behance me-2 text-primary"></i>Behance Creative</li>
                            <li><i class="bi bi-controller me-2 text-primary"></i>Game Community</li>
                        </ul>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection
