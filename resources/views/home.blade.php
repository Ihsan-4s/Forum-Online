@extends('templetes.app')

@section('content')
    {{-- Notifikasi sukses --}}


    {{-- Kalau user sudah login --}}
    @if (Auth::check())
        <div class="container" style="background-color: white; padding:20px; border-radius:10px; margin-top:20px;">
            <h3>Welcome, {{ Auth::user()->name }}!</h3>
            <p>What do you want to discuss today?</p>
            <form class="d-flex" role="search" method="GET" action="{{ route('home') }}">
                <input class="form-control me-2" type="search" name="q" value="{{ request('q') }}" placeholder="Search"
                    aria-label="Search" />
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

        </div>

        <div class="container mt-4">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 card p-4">
                    <div class="mb-4">
                        <h6>Filter berdasarkan</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="filter" id="selesai">
                            <label class="form-check-label" for="selesai">Diskusi sudah selesai</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="filter" id="belum">
                            <label class="form-check-label" for="belum">Diskusi belum selesai</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6>Urutkan berdasarkan</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="urutkan" id="terbaru" checked>
                            <label class="form-check-label" for="terbaru">Diskusi Terbaru</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="urutkan" id="terlama">
                            <label class="form-check-label" for="terlama">Diskusi Terlama</label>
                        </div>
                    </div>

                    <div>
                        <h6>Kata kunci populer</h6>
                        <span class="badge badge-secondary text-dark m-1">#error</span>
                        <span class="badge badge-secondary text-dark m-1">#postman</span>
                        <span class="badge badge-secondary text-dark m-1">#nodejs</span>
                        <span class="badge badge-secondary text-dark m-1">#submission</span>
                        <span class="badge badge-secondary text-dark m-1">#backend</span>
                        <!-- tambahin sesuai kebutuhan -->
                    </div>
                </div>

                <!-- Content -->
                <div class="col-md-9">
                    <!-- Card Diskusi -->
                    @if(count($threads))
                        @foreach ($threads as $thread)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <h5 class="me-3"><strong>{{ $thread->user->name }}</strong></h5>
                                        {{ $thread->created_at->diffForHumans() }}
                                    </div>
                                    <p><strong>{{ $thread->title }}</strong></p>
                                    <p>{{ $thread->content }}</p>
                                    <span class="badge badge-secondary text-dark">#{{ $thread->tag }}</span>
                                    <div class="d-flex justify-content-between mt-3">
                                        <small>1413 Pembahasan • Forum Diskusi</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            Belum ada diskusi yang tersedia.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="container " style="margin-top:20px;">
            <h3>Welcome, Guest!</h3>
            <p>Silakan <a href="{{ route('login.form') }}">login</a> untuk ikut berdiskusi.</p>
            {{-- threads/index.blade.php --}}
            @if(count($threads))
                @foreach ($threads as $thread)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>{{ $thread->title }}</h5>
                            <p>{{ $thread->content }}</p>
                            <small>by {{ $thread->user->name }}</small>
                            @if (Auth::check())
                                <a href="#" class="btn btn-sm btn-outline-success">Comment</a>
                            @else
                                <a href="{{ route('login.form') }}" class="btn btn-sm btn-outline-secondary">Login to
                                    comment</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <p>Belum ada thread.</p>
            @endif

        </div>
    @endif
@endsection
