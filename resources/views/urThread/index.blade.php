@extends('templetes.app')

@section('content')
<div class="container my-5">
    <h3 class="mb-4 text-center text-success fw-bold">
        📌 Thread Kamu
    </h3>

    <div class="row">
        {{-- Draft Section --}}
        <div class="col-md-6">
            <h5 class="fw-bold text-secondary mb-3">📝 Draft</h5>

            @if ($drafts->isEmpty())
                <div class="text-center text-muted my-5">
                    <img src="https://img.icons8.com/ios/100/cccccc/opened-folder.png" alt="empty" class="mb-3" />
                    <p>Belum ada draft.<br> Yuk mulai bikin diskusi!</p>
                    <a href="{{ route('threads.create') }}" class="btn btn-success mt-2" style="background-color:#18cb96; border:none;">
                        + Buat Diskusi Baru
                    </a>
                </div>
            @else
                @foreach ($drafts as $thread)
                    <div class="card shadow-sm mb-4 border-0" style="border-top:4px solid #ffc107;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-dark">{{ $thread->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($thread->content, 100) }}</p>
                            <span class="badge" style="background-color:#ffc107;">{{ $thread->tag }}</span>
                        </div>
                        <div class="card-footer d-flex justify-content-between bg-light">
                            <small class="text-muted">{{ $thread->created_at->format('d M Y') }}</small>
                            <div>
                                <a href="" class="btn btn-sm text-white" style="background-color:#ffc107;">Lanjutkan</a>
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Published Section --}}
        <div class="col-md-6">
            <h5 class="fw-bold text-success mb-3">✅ Published</h5>

            @if ($published->isEmpty())
                <div class="text-center text-muted my-5">
                    <img src="https://img.icons8.com/ios/100/cccccc/opened-folder.png" alt="empty" class="mb-3" />
                    <p>Belum ada thread yang dipublish.<br> Yuk publish diskusi kamu!</p>
                </div>
            @else
                @foreach ($published as $thread)
                    <div class="card shadow-sm mb-4 border-0" style="border-top:4px solid #18cb96;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-dark">{{ $thread->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($thread->content, 100) }}</p>
                            <span class="badge" style="background-color:#18cb96;">{{ $thread->tag }}</span>
                        </div>
                        <div class="card-footer d-flex justify-content-between bg-light">
                            <small class="text-muted">{{ $thread->created_at->format('d M Y') }}</small>
                            <div>
                                <a href="" class="btn btn-sm text-white" style="background-color:#18cb96;">Lihat</a>
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
