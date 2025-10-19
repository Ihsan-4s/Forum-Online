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
    </style>

    <div class="container">
        <div class="card create-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-pencil-square me-2 text-muted"></i>
                    <span class="text-muted fw-semibold">Edit Diskusi</span>
                </div>
            </div>

            <div class="card-body">
                {{-- 🔧 FIX: Tambahin enctype biar bisa upload file --}}
                <form id="threadForm" method="POST" action="{{ route('drafts.update', $draft->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Title --}}
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
                            placeholder="Edit judul diskusi..." value="{{ old('title', $draft->title) }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Content --}}
                    <div class="mb-4">
                        <textarea name="content" rows="3" class="form-control @error('content') is-invalid @enderror"
                            placeholder="Edit isi diskusimu...">{{ old('content', $draft->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Upload Image --}}
                    <div class="mb-4">
                        <label for="image" class="form-label">Upload Gambar</label>
                        @if ($draft->image)
                            <div class="mb-3 text-center">
                                <img src="{{ asset('storage/' . $draft->image) }}" alt="Gambar Diskusi"
                                    class="img-fluid rounded-3 shadow-sm" style="max-height: 250px; object-fit: cover;">
                                <p class="text-muted mt-2" style="font-size: 0.85rem;">Gambar saat ini</p>
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                    {{-- Tag --}}
                    <div class="mb-4">
                        <input type="text" name="tag" class="form-control @error('tag') is-invalid @enderror"
                            placeholder="#javascript #backend" value="{{ old('tag', $draft->tag) }}">
                        @error('tag')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-end align-items-center border-top pt-3">
                        <a href="{{ route('account.index') }}" class="btn btn-outline-secondary mx-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
