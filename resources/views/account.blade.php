@extends('templetes.app')
@section('content')
    <div class="container my-5">
        <h3 class="mb-3">Account Settings</h3>
        <p>Manage your account settings and preferences.</p>
        <div class="card border-0 shadow mb-4">
            <div class="card-body mx-5">
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative me-4">
                        @if (Auth::user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="profile"
                                class="rounded-circle border" width="120" height="120" style="object-fit: cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" alt="profile"
                                class="rounded-circle border" width="120" height="120" style="object-fit: cover">
                        @endif
                    </div>
                    <div>
                        <form action="{{ route('account.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label for="profile_picture" class="btn btn-outline-secondary mb-2">
                                Upload New Profile Picture
                            </label>
                            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" hidden
                                onchange="this.form.submit()">
                        </form>

                        <p class="text-muted small">
                            At least <strong>800×800 px</strong> recommended.<br>JPG or PNG is allowed.
                        </p>
                    </div>

                </div>
                <hr>
                <div class="card border-0 shadow mb-4 mx-3">
                    <div class="card-body mx-3">
                        <h4 class="mb-5">Personal Info</h4>
                        <div class="d-flex">
                            <div class="me-5">
                                <p class="text-muted">Nama Lengap</p>
                                <h5>{{ Auth::user()->name }}</h5>
                            </div>
                            <div>
                                <p class="text-muted">Email</p>
                                <h5>{{ Auth::user()->email }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4>Your Threads</h4>
                    <div>
                        <ul class="nav nav-tabs mb-4" id="threadsTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="threads-tab" data-bs-toggle="tab"
                                    data-bs-target="#threads" type="button" role="tab">Threads</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="drafts-tab" data-bs-toggle="tab" data-bs-target="#drafts"
                                    type="button" role="tab">Drafts</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="threadsTabContent">
                            <div class="tab-pane fade show active" id="threads" role="tabpanel"
                                aria-labelledby="threads-tab">
                                @forelse ($threads as $thread)
                                    <div class="card mb-3 border-0 shadow-sm p-3 rounded-3">
                                        <div class="d-flex align-items-start">
                                            @if (Auth::user()->profile_picture)
                                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                                    class="rounded-circle me-3" width="45" height="45"
                                                    alt="{{ Auth::user()->name }}">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
                                                    class="rounded-circle me-3" width="45" height="45"
                                                    alt="{{ Auth::user()->name }}">
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="fw-semibold mb-0">{{ $thread->user->name }}</h6>
                                                        <small
                                                            class="text-muted">{{ $thread->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </div>

                                                <div class="mt-2">
                                                    <h5 class="fw-bold mb-1 text-dark">{{ $thread->title }}</h5>
                                                    @if ($thread->image)
                                                        <img src="{{ asset('storage/' . $thread->image) }}"
                                                            class="rounded mb-2" width="200">
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
                                                        <a href="{{ route('threads.show', $thread) }}"
                                                            class="btn btn-outline-secondary btn-sm">
                                                            {{ $thread->comments_count }} Pembahasan
                                                        </a>
                                                        <form action="{{ route('threads.destroy', $thread) }}"
                                                            method="POST" class="d-inline">
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
                            <div class="tab-pane fade" id="drafts" role="tabpanel" aria-labelledby="drafts-tab">
                                @if (isset($drafts) && $drafts->count())
                                    @foreach ($drafts as $draft)
                                        <div class="card mb-3 border-0 shadow-sm p-3 rounded-3">
                                            <div class="d-flex align-items-start">
                                                {{-- Profile user --}}
                                                @if ($draft->user->profile_picture)
                                                    <img src="{{ asset('storage/' . $draft->user->profile_picture) }}"
                                                        class="rounded-circle me-3" width="45" height="45"
                                                        alt="{{ $draft->user->name }}">
                                                @else
                                                    <img src="https://ui-avatars.com/api/?name={{ $draft->user->name }}"
                                                        class="rounded-circle me-3" width="45" height="45"
                                                        alt="{{ $draft->user->name }}">
                                                @endif

                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="fw-semibold mb-0">{{ $draft->user->name }}</h6>
                                                            <small
                                                                class="text-muted">{{ $draft->created_at->diffForHumans() }}</small>
                                                        </div>
                                                        <span
                                                            class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1">
                                                            DRAFT
                                                        </span>
                                                    </div>

                                                    <div class="mt-2">
                                                        <h5 class="fw-bold mb-1 text-dark">{{ $draft->title }}</h5>
                                                        <p class="text-muted mb-2" style="font-size: 0.95rem;">
                                                            {{ Str::limit($draft->content, 80, '...') }}
                                                        </p>

                                                        @if ($draft->image)
                                                            <img src="{{ asset('storage/' . $draft->image) }}"
                                                                alt="draft" class="rounded mb-2" width="200">
                                                        @endif

                                                        {{-- Tags --}}
                                                        <div class="mb-2">
                                                            @foreach ($draft->tags as $tag)
                                                                <a
                                                                    class="badge bg-light text-dark border text-decoration-none">
                                                                    #{{ $tag->name }}
                                                                </a>
                                                            @endforeach
                                                        </div>

                                                        {{-- Action buttons --}}
                                                        <div class="d-flex">
                                                            <div class="ms-auto d-flex gap-2">
                                                                <a href="{{ route('drafts.edit', $draft->id) }}"
                                                                    class="btn btn-outline-primary">Edit</a>

                                                                <form action="{{ route('drafts.destroy', $draft->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-outline-danger">Delete</button>
                                                                </form>

                                                                <form action="{{ route('drafts.publish', $draft->id) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Upload draft ini?')">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit"
                                                                        class="btn btn-outline-success">Upload</button>
                                                                </form>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">Belum ada draft.</p>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
