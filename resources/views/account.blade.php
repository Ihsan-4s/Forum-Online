@extends('templetes.app')
@section('content')
    <div class="container my-5">
        <h3 class="mb-3">Account Settings</h3>
        <p>Manage your account settings and preferences.</p>
        <div class="card border-0 shadow mb-4">
            <div class="card-body mx-5">
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative me-4">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" alt="profile"
                            class="rounded-circle border" width="120" height="120" style="object-fit: cover">
                    </div>
                    <div>
                        <form action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="btn btn-outline-secondary mb-2">Upload New Profile Picture</label>
                            <input type="file" name="profile" hidden onchange="this.form.submit()">
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
                                            <img src="https://ui-avatars.com/api/?name={{ $thread->user->name }}"
                                                class="rounded-circle me-3" width="45" height="45"
                                                alt="{{ $thread->user->name }}">
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="fw-semibold mb-0">{{ $thread->user->name }}</h6>
                                                        <small
                                                            class="text-muted">{{ $thread->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <span
                                                        class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">SELESAI</span>
                                                </div>

                                                <div class="mt-2">
                                                    <h5 class="fw-bold mb-1 text-dark">{{ $thread->title }}</h5>
                                                    @if ($thread->image)
                                                        <img src="{{ asset('storage/' . $thread->image) }}"
                                                            class="rounded mb-2" width="200">
                                                    @endif
                                                    <p class="text-muted mb-2" style="font-size: 0.95rem;">
                                                        {{ Str::limit($thread->content, 180, '...') }}
                                                    </p>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <span
                                                        class="badge bg-light text-dark border">#{{ $thread->tag }}</span>
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
                                @empty
                                    <p class="text-muted">You have not created any threads yet.</p>
                                @endforelse
                            </div>
                            <div class="tab-pane fade" id="drafts" role="tabpanel" aria-labelledby="drafts-tab">
                                @if (isset($drafts))
                                    {{-- tampilkan draft --}}
                                    @forelse ($drafts as $draft)
                                        <div class="card mb-3 border-0 shadow-sm p-3 rounded-3">
                                            <div class="d-flex align-items-start">
                                                <img src="https://ui-avatars.com/api/?name={{ $draft->user->name }}"
                                                    class="rounded-circle me-3" width="45" height="45"
                                                    alt="{{ $draft->user->name }}">
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="fw-semibold mb-0">{{ $draft->user->name }}</h6>
                                                            <small
                                                                class="text-muted">{{ $draft->created_at->diffForHumans() }}</small>
                                                        </div>
                                                        <span
                                                            class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1">DRAFT</span>
                                                    </div>

                                                    <div class="mt-2">
                                                        <h5 class="fw-bold mb-1 text-dark">{{ $draft->title }}</h5>
                                                        <p class="text-muted mb-2" style="font-size: 0.95rem;">
                                                            {{ Str::limit($draft->content, 180, '...') }}
                                                        </p>
                                                    </div>
                                                    <div class="d-flex ">
                                                        <a href="{{ route('drafts.edit', $draft->id) }}" class="btn btn-primary">Edit</a>
                                                        <form action="{{ route('drafts.destroy', $draft->id) }}" method="POST" class="ms-2">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted">Belum ada draft.</p>
                                    @endforelse
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
