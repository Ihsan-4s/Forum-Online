@extends('templetes.app')
@section('content')
    <div class="container my-5">
        <h1>Reported Comments</h1>
        <a href="{{ route('admin.comments.export') }}" class="btn btn-warning mb-3">
            Export Excel
        </a>
        <table class="table table-bordered">
            <thead>
            <tr>

                <th>Comments</th>
                <th>Reason</th>
                <th>Actions</th>
            </tr>
            </thead>
            {{-- @foreach ($reportedComments as $comment)
                <tr>
                    <td>{{ $comment->content }}</td>
                    <td>{{ $comment->report_reason }}</td>
                    <td>
                        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>

                    </td>
                </tr>
            @endforeach --}}
        </table>
    </div>
@endsection
@push('script')
    <script>
        $(function(){
            $('table').DataTable({
                processing:true,
                serverSide:true,
                ajax:'{{ route('admin.comments.data') }}',
                columns:[
                    {data:'content', name:'content', orderable:true, searchable:true},
                    {data:'report_reason', name:'report_reason' , orderable:true, searchable:true},
                    {data:'actions', name:'actions', orderable:false, searchable:false},
                ]
            })
        })
    </script>
@endpush
