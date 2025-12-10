<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @foreach ($threads as $thread)
        <h1>{{ $thread->title }}</h1>
        @if ($thread->image)
            <img src="{{ public_path('storage/' . $thread->image) }}" width="200">
        @endif
        <p>{{ $thread->content }}</p>
        <p>Created by: {{ $thread->user->name }} • {{ $thread->created_at->diffForHumans() }}</p>
        @if ($thread->tags->isNotEmpty())
            <div>
                @foreach ($thread->tags as $tag)
                    <span>#{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif
        <hr>
    @endforeach
</body>
</html>
