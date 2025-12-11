@extends('templetes.app')
@section('content')
    <div class="container my-5">
        <h1>Reported Threads Data</h1>
        <a href="{{ route('admin.threads.export') }}" class="btn btn-warning mb-3">
            Export Excel
        </a>

            <div class="row mt-5">
                <div class="col-6">
                    <canvas id="chartBar" class="col-6"></canvas>
                </div>
            </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Threads</th>
                    <th>Actions</th>
                </tr>
            </thead>
            {{-- @foreach ($threads as $thread)
                <tr>
                    <td class="p-3">
                        <div class="border rounded-3 bg-light-subtle p-3 shadow-sm">
                            Header User
                            <div class="d-flex align-items-center mb-2">
                                @if ($thread->user->profile_picture)
                                    <img src="{{ asset('storage/' . $thread->user->profile_picture) }}"
                                        class="rounded-circle border me-2" width="45" height="45"
                                        style="object-fit: cover;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ $thread->user->name }}"
                                        class="rounded-circle border me-2" width="45" height="45"
                                        style="object-fit: cover;">
                                @endif
                                <div>
                                    <h6 class="fw-semibold mb-0">{{ $thread->user->name }}</h6>
                                    <small class="text-muted">{{ $thread->created_at->diffForHumans() }}</small>
                                </div>
                                Status User
                                <span class="badge bg-secondary ms-auto">{{ ucfirst($thread->user->role ?? 'user') }}</span>
                            </div>

                            Konten Thread
                            <div class="mt-2">
                                <h5 class="fw-bold mb-2">{{ $thread->title }}</h5>
                                @if ($thread->image)
                                    <img src="{{ asset('storage/' . $thread->image) }}" class="rounded mb-2 d-block"
                                        width="300" style="object-fit: cover;">
                                @endif
                                <p class="text-muted mb-3" style="font-size: 0.95rem;">
                                    {{ Str::limit(strip_tags($thread->content), 150, '...') }}
                                </p>
                            </div>

                            Tags
                            <div class="mb-3">
                                @foreach ($thread->tags as $tag)
                                    <span class="badge bg-light text-dark border">#{{ $tag->name }}</span>
                                @endforeach
                            </div>

                            Footer: Likes, Komentar, Aksi Admin
                            <div class="d-flex justify-content-between align-items-center border-top pt-2">
                                <div>
                                    <span class="me-3">👍 {{ $thread->likes()->count() }} Likes</span>
                                    <span>💬 {{ $thread->comments_count }} Komentar</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        {{ $thread->report_reason }}
                    </td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('threads.show', $thread) }}" class="btn  btn-primary">
                                Lihat
                            </a>

                            <form action="{{ route('threads.destroy', $thread) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus thread ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn  btn-danger">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
            @endforeach --}}
        </table>
    </div>
@endsection
@push('script')
    <script>
        let labels = null;
        let data = null;

        $(function(){
            $('table').DataTable({
                processing:true,
                serverSide:true,
                ajax:'{{ route('admin.threads.data')}}',
                columns:[
                    {data:'title', name:'title', orderable:true, searchable:true},
                    {data:'actions', name:'actions', orderable:false, searchable:false},
                ]
            })
        });

        $(function(){
            $.ajax({
                url: "{{ route('admin.threads.reportedChart') }}",
                method: "GET",
                success: function(response){
                    labels = response.labels;
                    data = response.data;
                    chartBar();
                },
                error: function(err) {
                    alert('Gagal Membuat Chart');
                }
            })
        });
        const ctx = document.getElementById('chartBar')
        function chartBar(){
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets : [{
                        label : 'Jumlah Thread Dilaporkan Bulan Ini',
                        data: data,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales:{
                        y:{
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
@endpush
