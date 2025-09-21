@extends('templetes.app')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center">
            <h3><b>Create Threads</b></h3>
            <button type="button" class="btn btn-light ">Draft</button>
        </div>
        <div class="d-flex justify-content-start mt-4">
            <div class="w-50">
                <form method="POST" action="{{route('threads.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" >
                    </div>
                    <div class="mb-3">
                        <label for="threadContent" class="form-label">Content</label>
                        <textarea class="form-control" id="threadContent" rows="5" cols="30" name="content"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Tag</label>
                        <input type="text" class="form-control" name="tag" >
                    </div>
                    <button type="submit" class="btn btn-primary">Post</button>
                </form>
            </div>
        </div>
    </div>
@endsection
