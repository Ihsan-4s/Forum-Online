@extends('templetes.app')
@section('content')
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Buat Diskusi Baru</h5>
            </div>
            <div class="card-body">
                <form id="threadForm" method="POST" action="{{ route('threads.store') }}">
                    @csrf
                    {{-- Title --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Diskusi</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            placeholder="Masukkan judul diskusi">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Content --}}
                    <div class="mb-3">
                        <label for="content" class="form-label">Isi Diskusi</label>
                        <textarea name="content" rows="5" class="form-control @error('content') is-invalid @enderror"
                            placeholder="Tulis isi diskusi kamu"></textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tag --}}
                    <div class="mb-3">
                        <label for="tag" class="form-label">Tag</label>
                        <input type="text" name="tag" class="form-control @error('tag') is-invalid @enderror"
                            placeholder="#javascript #backend">
                        <div class="form-text">Pisahkan tag dengan spasi atau tanda koma.</div>
                        @error('tag')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex justify-content-end">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary mx-3" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success">Kirim Diskusi</button>
                    </div>
                </form>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Menjadikan ini sebagai draft akan menyimpan progressmu dalam membuat diskusi
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Lanjutkan</button>
                                <button type="submit" class="btn btn-primary" onclick="saveAsDraft()">Jadikan Draft</button>
                                <a href="{{ route('index') }}" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function saveAsDraft(){
            let form = document.getElementById('threadForm')
            form.action= "{{ route('drafts.store') }}";
            form.submit();
        }
    </script>
@endsection
