@extends('templetes.app')
@section('content')
    <div class="container my-5">
        <h1>Data User</h1>

        <table id="userTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>

            <div class="row mt-5">
                <div class="col-6">
                    <canvas id="chartBar"></canvas>
                </div>
            </div>
            {{-- <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>

                        <td>
                            <form action="{{ route('admin.active', $user->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                @if ($user->is_active == 1)
                                    <button type="submit" class="btn btn-danger ms-2">Non Aktifkan</button>
                                @endif
                            </form>
                            <form action="{{ route('admin.deactive', $user->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                @if ($user->is_active == 0)
                                    <button type="submit" class="btn btn-success ms-2">Aktifkan</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody> --}}
        </table>
    </div>
@endsection
@push('script')
    <script>
        let labels = null;
        let data = null;
        $(function(){
            $.ajax({
                url: "{{ route('admin.userChart') }}",
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
                        label : 'Pembuatan Account Bulan Ini',
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

        $(function(){
            $('#userTable').DataTable({
                processing:true,
                serverSide:true,
                ajax:'{{ route('admin.dataTable') }}',
                columns:[
                    {data:'name', name:'name', orderable:true, searchable:true},
                    {data:'email', name:'email' , orderable:true, searchable:true},
                    {data:'action', name:'action', orderable:false, searchable:false},
                ]
            })
        })
    </script>
@endpush
