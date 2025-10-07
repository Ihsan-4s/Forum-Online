@extends('templetes.app')
@section('content')
    <div class="container">

        <h1 class="text-2xl font-bold">{{ $thread->title }}</h1>
        <p class="text-gray-700 mt-2">{{ $thread->content }}</p>

        <p class="text-sm text-gray-500 mt-1">
            Dibuat oleh: {{ $thread->user->name }} • {{ $thread->created_at->diffForHumans() }}
        </p>

        @if ($thread->tags->isNotEmpty())
            <div class="mt-2">
                @foreach ($thread->tags as $tag)
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm">{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif

        <hr class="my-6">

        <h2 class="text-xl font-semibold mb-2">Komentar</h2>

        @foreach ($thread->comments as $comment)
            <div class="border-t py-2">
                <strong>{{ $comment->user->name }}</strong>
                <p>{{ $comment->content }}</p>
                <small class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
        @endforeach

        @auth
            <form action="" method="POST" class="mt-4">
                @csrf
                <textarea name="content" class="w-full border rounded p-2" rows="3" placeholder="Tulis komentar..." required></textarea>
                <button type="submit" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded">Kirim</button>
            </form>
        @endauth

    </div>
@endsection
