@extends('templetes.app')
@section('content')
    <style>
        body {
            background-color: #f4f6f8;
        }

        .create-card {
            max-width: 650px;
            margin: 80px auto;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: none;
        }

        .card-header {
            border: none;
            background: transparent;
            padding: 1.5rem 2rem 0;
        }

        .card-body {
            padding: 1.5rem 2rem;
        }

        .form-control {
            border: none;
            border-bottom: 1px solid #e0e0e0;
            border-radius: 0;
            padding: 0.6rem 0;
            font-size: 0.95rem;
            transition: 0.2s;
            background: transparent;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: none;
        }

        .form-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .upload-btn {
            border: 1px solid #3b82f6;
            background: #fff;
            color: #3b82f6;
            font-weight: 500;
            border-radius: 50px;
            padding: 0.4rem 1.2rem;
            transition: 0.2s;
        }

        .upload-btn:hover {
            background: #3b82f6;
            color: #fff;
        }

        .icon-btn {
            border: none;
            background: transparent;
            color: #6b7280;
            font-size: 1.2rem;
            margin-right: 10px;
        }

        .icon-btn:hover {
            color: #3b82f6;
        }

        .modal-content {
            border-radius: 15px;
        }
    </style>

    <div class="container">
        @if (Session::get('success'))
            <div class="alert alert-success mb-4">{{ Session::get('success') }}</div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger mb-4">{{ Session::get('error') }}</div>
        @endif
        <div class="card create-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-folder2 me-2 text-muted"></i>
                    <i class="bi bi-star text-muted"></i>
                </div>

            </div>

            <div class="card-body">
                <form id="threadForm" method="POST" action="{{ route('threads.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- User Avatar & Title --}}
                    <div class="d-flex align-items-center mb-4">
                        @if (Auth::user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="rounded-circle me-3"
                                width="50" height="50" alt="{{ Auth::user()->name }}">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" class="rounded-circle me-3"
                                width="50" height="50" alt="{{ Auth::user()->name }}">
                        @endif
                        <input type="text" name="title"
                            class="form-control fs-5 fw-semibold border-0 border-bottom @error('title') is-invalid @enderror"
                            placeholder="What's new?">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Content --}}
                    <div class="mb-4">
                        <textarea name="content" rows="1" class="form-control @error('content') is-invalid @enderror"
                            placeholder="Tulis isi diskusi kamu di sini..."></textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Upload Image --}}
                    <div class="mb-4">
                        <label for="image" class="form-label">Upload Gambar</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                    {{-- Tag --}}
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags (pisahkan dengan koma)</label>
                        <input type="text" name="tags" id="tags" class="form-control"
                            placeholder="contoh: laravel, php, backend">
                    </div>


                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        <div>
                            <button type="button" class="icon-btn"><i class="bi bi-emoji-smile"></i></button>
                            <button type="button" class="icon-btn"><i class="bi bi-paperclip"></i></button>
                            <button type="button" class="icon-btn"><i class="bi bi-heart"></i></button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-outline-secondary rounded-pill mx-2"
                                data-bs-toggle="modal" data-bs-target="#exampleModal">Batal</button>
                            <button type="submit" class="upload-btn">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Draft --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" id="exampleModalLabel">Simpan sebagai Draft?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-muted">
                    Menjadikan ini sebagai draft akan menyimpan progressmu dalam membuat diskusi.
                </div>
                <div class="modal-footer border-0">
                    <button class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Lanjutkan</button>
                    <button type="submit" class="btn btn-primary rounded-pill" onclick="saveAsDraft()">Jadikan
                        Draft</button>
                    <a href="{{ route('index') }}" class="btn btn-outline-primary rounded-pill">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function saveAsDraft() {
            let form = document.getElementById('threadForm')
            form.action = "{{ route('drafts.store') }}";
            form.submit();
        }
    </script>
@endsection
