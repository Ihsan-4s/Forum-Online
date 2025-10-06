@extends('templetes.app')

@section('content')
    <div class="container-fluid py-4 px-5 bg-light">
        @if (Session::get('success'))
            <div class="alert alert-success mb-4">{{ Session::get('success') }}</div>
        @endif

        <div class="row">
            {{-- KIRI - Profil & Shortcut --}}
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body text-center">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" class="rounded-circle mb-3"
                            width="80">
                        <h6 class="fw-bold mb-0">{{ Auth::user()->name }}</h6>
                        <p class="text-muted small">{{ '@' . Str::slug(Auth::user()->name, '_') }}</p>
                        <a href="#" class="btn btn-primary btn-sm w-100 rounded-pill">My Profile</a>
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

            {{-- TENGAH - Feed Diskusi --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body d-flex align-items-center">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" class="rounded-circle me-3"
                            width="50">
                        <input type="text" class="form-control rounded-pill" placeholder="Tulis sesuatu...">
                    </div>
                    <div class="card-footer bg-white">
                        <button class="btn btn-outline-secondary btn-sm me-2"><i class="bi bi-image"></i> Image</button>
                        <button class="btn btn-outline-secondary btn-sm me-2"><i class="bi bi-camera-video"></i>
                            Video</button>
                        <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-bar-chart"></i> Poll</button>
                    </div>
                </div>

                @foreach ($threads as $thread)
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <img src="https://ui-avatars.com/api/?name={{ $thread->user->name }}"
                                    class="rounded-circle me-2" width="40">
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $thread->user->name }}</h6>
                                    <small class="text-muted">{{ $thread->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            <h5>{{$thread->title}}</h5>
                            <img src="{{ asset('storage/' . $thread['image']) }}" class="rounded me-2" width="200">
                            <p class="mb-2">{{ $thread->content }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-light text-dark border">#{{ $thread->tag }}</span>
                                <div class="text-muted small">
                                    <i class="bi bi-heart me-1"></i>18
                                    <i class="bi bi-chat-left-text ms-3 me-1"></i>5
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- KANAN - Aktivitas & Saran --}}
            <div class="col-lg-3">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Aktivitas Terbaru</h6>
                        <p class="small mb-1"><strong>Dera</strong> started following you · 5m</p>
                        <p class="small mb-1"><strong>Ediwp</strong> liked your photo · 30m</p>
                        <p class="small mb-0"><strong>Praha_</strong> liked your photo · 1d</p>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Disarankan untuk Anda</h6>
                        <div class="d-flex align-items-center mb-2">
                            <img src="https://ui-avatars.com/api/?name=Najid" class="rounded-circle me-2" width="35">
                            <div class="flex-grow-1">
                                <small class="fw-bold d-block">Najid</small>
                                <button class="btn btn-outline-primary btn-sm py-0 px-2">Follow</button>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name=Sheila+Dara" class="rounded-circle me-2"
                                width="35">
                            <div class="flex-grow-1">
                                <small class="fw-bold d-block">Sheila Dara</small>
                                <button class="btn btn-outline-primary btn-sm py-0 px-2">Follow</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
