<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ThreadController extends Controller
{
    public function index()
{
    $threads = Thread::with(['user', 'tags'])
    ->withCount(['comments', 'likes'])
    ->orderByDesc('likes_count')
    ->orderByDesc('created_at') // tiebreaker
    ->get();


    $topLikedThreads = Thread::withCount('likes')
        ->orderByDesc('likes_count')
        ->take(5)
        ->get();

    $popularTags = \App\Models\Tag::withCount('threads')
        ->orderByDesc('threads_count')
        ->take(10)
        ->get();

    return view('thread.index', [
        'threads' => $threads,
        'popularTags' => $popularTags,
        'topLikedThreads' => $topLikedThreads,
        'tagName' => null
    ]);
}




    public function create()
    {
        $tags = \App\Models\Tag::all();
        return view('thread.create', compact('tags'));

    }


public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'content' => 'required',
        'tags' => 'required|string',
        'image' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048'
    ]);

    $filePath = null;
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = 'image-' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('image', $fileName, 'public');
    }

    // Buat thread baru
    $thread = Thread::create([
        'title' => $request->title,
        'content' => $request->content,
        'image' => $filePath,
        'user_id' => Auth::id(),
        'status' => 'published'
    ]);

    $tagNames = array_map('trim', explode(',', $request->tags));
    $tagIds = [];
    foreach ($tagNames as $tagName) {
        if ($tagName === '') continue;
        $tag = \App\Models\Tag::firstOrCreate(['name' => strtolower($tagName)]);
        $tagIds[] = $tag->id;
    }
    $thread->tags()->attach($tagIds);
    return redirect()->route('index')->with('success', 'Thread berhasil dibuat!');
}

    public function filterByTag($tagName)
{
    // Ambil semua thread yang punya tag dengan nama sesuai $tagName
    $threads = Thread::whereHas('tags', function ($q) use ($tagName) {
        $q->where('name', $tagName);
    })
    ->with(['user', 'tags'])
    ->withCount('comments')
    ->latest()
    ->get();

    $popularTags = \App\Models\Tag::withCount('threads')
        ->orderByDesc('threads_count')
        ->take(10)
        ->get();

    return view('thread.index', [
        'threads' => $threads,
        'popularTags' => $popularTags,
        'tagName' => $tagName
    ]);
}


    public function draftIndex(){
        $drafts = Thread::where('user_id', Auth::id())
                ->where('status', 'draft')
                ->latest()
                ->get();

        return view('account',  compact('drafts'));
    }



// Simpan draft
public function draftStore(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'tags' => 'nullable|string',
        'image' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
    ]);

    $filePath = null;
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = 'draft-' . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('drafts', $fileName, 'public');
    }

    $draft = Thread::create([
        'title' => $request->title,
        'content' => $request->content,
        'image' => $filePath,
        'user_id' => Auth::id(),
        'status' => 'draft',
    ]);

    if ($request->tags) {
        $tagNames = array_map('trim', explode(',', $request->tags));
        $tagIds = [];
        foreach ($tagNames as $name) {
            if ($name === '') continue;
            $tag = \App\Models\Tag::firstOrCreate(['name' => strtolower($name)]);
            $tagIds[] = $tag->id;
        }
        $draft->tags()->sync($tagIds);
    }

    return redirect()->route('account.index')->with('success', 'Draft berhasil dibuat!');
}

// Form edit draft
public function draftEdit($id)
{
    $draft = Thread::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->where('status', 'draft')
                    ->firstOrFail();
    $tags = \App\Models\Tag::all();

    return view('draft.edit', compact('draft', 'tags'));
}

// Update draft
public function draftUpdate(Request $request, $id)
{
    $draft = Thread::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->where('status', 'draft')
                    ->firstOrFail();

    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'tags' => 'nullable|string',
        'image' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
    ]);

    // Update gambar
    if ($request->hasFile('image')) {
        if ($draft->image) {
            Storage::disk('public')->delete($draft->image);
        }
        $file = $request->file('image');
        $fileName = 'draft-' . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
        $draft->image = $file->storeAs('drafts', $fileName, 'public');
    }

    $draft->title = $request->title;
    $draft->content = $request->content;
    $draft->save();

    // Update tags
    if ($request->tags) {
        $tagNames = array_map('trim', explode(',', $request->tags));
        $tagIds = [];
        foreach ($tagNames as $name) {
            if ($name === '') continue;
            $tag = \App\Models\Tag::firstOrCreate(['name' => strtolower($name)]);
            $tagIds[] = $tag->id;
        }
        $draft->tags()->sync($tagIds);
    }

    return redirect()->route('account.index')->with('success', 'Draft berhasil diperbarui!');
}


    public function draftDestroy($id)
    {
        $draft = Thread::findOrFail($id);

        // Hapus gambar terkait jika ada
        if ($draft->image) {
            Storage::disk('public')->delete($draft->image);
        }

        $draft->delete();

        return redirect()->route('account.index')->with('success', 'Draft berhasil dihapus');
    }

    public function draftPublish($id)
    {
        $draft = Thread::findOrFail($id);
        $draft->status = 'published';
        $draft->save();

        return redirect()->route('account.index')->with('success', 'Draft berhasil dipublikasikan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Thread $thread)
    {
        $thread->load(['user','comments.user','tags']);
        return view('thread.show',compact('thread'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Thread $thread)
    {
        $thread->delete();
        return redirect()->back()->with('success', 'Thread berhasil dihapus');
    }

    public function urThreadIndex()
    {
        $drafts = Thread::where('user_id', Auth::id())
                    ->where('status', 'draft')
                    ->latest()
                    ->get();

        $published = Thread::where('user_id', Auth::id())
                    ->where('status', 'published')
                    ->latest()
                    ->get();

        return view('urThread.index', compact('drafts', 'published'));
    }

}
