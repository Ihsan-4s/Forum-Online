@extends('templetes.app')

@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    <div class="container" style="background-color: white; padding:20px; border-radius:10px; margin-top:20px;">
        <h3>Welcome, {{ Auth::user()->name }}!</h3>
        <p>What do you want to discuss today?</p>
        <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>
    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
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
                @foreach ( $threads as $key => $value )
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="me-3"><strong> {{ Auth::user()->name }}</strong></h5>
                            {{ $value->created_at->diffForHumans() }}
                        </div>
                        <p><strong> {{ $value['title'] }}</strong></p>
                        <p>{{ $value['content'] }}</p>
                        <span class="badge badge-secondary text-dark">#{{$value['tag'] }}</span>
                        <div class="d-flex justify-content-between mt-3">
                            <small>1413 Pembahasan • Forum Diskusi</small>
                            <span class="badge bg-success">SELESAI</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
