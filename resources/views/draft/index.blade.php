@extends('templetes.app')

@section('content')
    <div class="container my-5">
        <h3 class="mb-4 text-center text-success fw-bold">
            📌 Draft Diskusi Kamu
        </h3>

        @if ($drafts->isEmpty())
            {{-- Kalau kosong --}}
            <div class="text-center text-muted my-5">
                <img src="https://img.icons8.com/ios/100/cccccc/opened-folder.png" alt="empty" class="mb-3" />
                <p>Belum ada draft yang kamu simpan.<br> Yuk mulai bikin diskusi seru!</p>
                <a href="{{ route('threads.create') }}" class="btn btn-success mt-2" style="background-color:#18cb96; border:none;">
                    + Buat Diskusi Baru
                </a>
            </div>
        @else
            <div class="row">
                @foreach ($drafts as $draft)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100 border-0" style="border-top:4px solid #18cb96;">
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-dark">{{ $draft['title'] }}</h5>
                                <p class="card-text text-muted">{{ $draft['content'] }}</p>
                                <span class="badge" style="background-color:#18cb96;">{{ $draft['tag'] }}</span>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light">
                                <small class="text-muted">{{ $draft['created_at'] }}</small>
                                <div>
                                    <a href="{{ route('drafts.edit', $draft->id) }}"
                                        class="btn btn-sm text-white" style="background-color:#18cb96;">Lanjutkan</a>
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
