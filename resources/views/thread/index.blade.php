@extends('templetes.app')

@section('content')
    <div class="container my-5">

        {{-- Hero Section --}}
        <div class="p-4 mb-4 bg-light rounded shadow-sm">
            <h4 class="mb-1">Selamat Datang di Forum Diskusi </h4>
            <p class="text-muted">Konsultasi seputar materi belajar Anda.</p>
        </div>

        <div class="row">
            {{-- Sidebar kiri --}}
            <div class="col-md-3 mb-4">
                <div class="position-sticky" style="top: 20px;">
                    <button class="btn btn-success w-100 mb-3">Buat diskusi baru</button>
                    <a href="{{ route('drafts.index') }}" class="btn btn-outline-success w-100 mb-4">Draft</a>

                    <h6 class="fw-bold">Filter berdasarkan</h6>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="filter" id="selesai">
                        <label class="form-check-label" for="selesai">Diskusi sudah selesai</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="filter" id="belum">
                        <label class="form-check-label" for="belum">Diskusi belum selesai</label>
                    </div>

                    <h6 class="fw-bold">Urutkan berdasarkan</h6>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="urutkan" id="baru" checked>
                        <label class="form-check-label" for="baru">Diskusi Terbaru</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="urutkan" id="lama">
                        <label class="form-check-label" for="lama">Diskusi Terlama</label>
                    </div>

                    <h6 class="fw-bold">Kata kunci populer</h6>
                    <div class="d-flex flex-wrap gap-1">
                        <span class="badge bg-light text-dark border">#error</span>
                        <span class="badge bg-light text-dark border">#postman</span>
                        <span class="badge bg-light text-dark border">#nodejs</span>
                        <span class="badge bg-light text-dark border">#backend</span>
                        <span class="badge bg-light text-dark border">#api</span>
                    </div>
                </div>
            </div>

            {{-- Konten utama --}}
            <div class="col-md-9">
                {{-- Search dan filter --}}
                <form class="d-flex " role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>

                {{-- Card diskusi --}}
                @foreach ( $threads as $thread )
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title fw-bold">{{$thread['title']}}</h6>
                        <p class="text-muted small mb-2">{{ $thread->user->name }} . {{ $thread->created_at->diffForHumans() }} </p>
                        <p class="card-text">{{$thread['content']}}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                <span class="badge bg-secondary">{{$thread['tag']}}</span>
                            </span>
                            <span class="text-success fw-bold">SELESAI</span>
                        </div>
                    </div>
                    <div class="card-footer text-muted small">
                        18 Pembahasan • Latihan: Same-Origin Policy
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
