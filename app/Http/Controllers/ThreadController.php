<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ThreadController extends Controller
{
    public function explore(Request $request)
    {
        $searchQuery = $request->search_threads;
        if ($searchQuery) {
            $threads = Thread::where('title', 'like', '%' . $searchQuery . '%')
                ->orWhere('content', 'like', '%' . $searchQuery . '%')
                ->with(['user', 'tags'])
                ->withCount(['comments', 'likes'])
                ->orderByDesc('created_at')
                ->get();

            $popularTags = \App\Models\Tag::withCount('threads')
                ->orderByDesc('threads_count')
                ->take(10)
                ->get();

            return view('thread.explore', [
                'threads' => $threads,
                'popularTags' => $popularTags,
                'tagName' => null
            ]);
        }

        $threads = Thread::with(['user', 'tags'])
            ->withCount(['comments', 'likes'])
            ->orderByDesc('created_at')
            ->get();

        $popularTags = \App\Models\Tag::withCount('threads')
            ->orderByDesc('threads_count')
            ->take(10)
            ->get();

        return view('thread.explore', [
            'threads' => $threads,
            'popularTags' => $popularTags,
            'tagName' => null
        ]);
    }

    public function index()
{
    $threads = Thread::with(['user', 'tags'])
    ->orderByDesc('created_at')
    ->get();

    return view('thread.index', [
        'threads' => $threads,
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
        'tags' => 'nullable|string',
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

    public function report(Request $request, Thread $thread)
{
    $request->validate([
        'reason' => 'required|string|max:255',
    ]);

    $thread->update([
        'is_reported' => true,
        'report_reason' => $request->reason,
    ]);

    return back()->with('success', 'Thread berhasil dilaporkan.');
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

    public function adminThreads()
    {
        $threads = Thread::where('is_reported', true)->get();
        return view('admin.threads', compact('threads'));
    }

    public function exportReportedThreads()
    {
        $fileName = 'reported_threads_' . date('Y_m_d_H_i_s') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ReportedExport, $fileName);

    }

    public function trash()
    {
        $threads = Thread::onlyTrashed()
        ->where('user_id', Auth::id())
        ->get();
        return view('thread.trash' , compact('threads'));
    }

    public function restore($id)
    {
        $thread = Thread::withTrashed()->find($id);
        $thread->restore();
        return redirect()->route('index')->with('success', 'Data Berhasil Direstore');
    }

    public function deletePermanent($id)
    {
        $thread = Thread::onlyTrashed()->find($id);
        $thread->forceDelete();
        return redirect()->back();
    }

    public function threadsData()
    {
        $threads = Thread::with('user')->where('is_reported', true)->get();
        return Datatables::of($threads)
            ->addIndexColumn()
            ->addColumn('title', function ($thread) {

        $image = $thread->image
            ? '<img src="'.asset("storage/".$thread->image).'" width="300" class="rounded mb-2 d-block">'
            : '';

        $profile = $thread->user->profile_picture
            ? asset("storage/".$thread->user->profile_picture)
            : "https://ui-avatars.com/api/?name=" . $thread->user->name;

        return '
        <div class="border rounded-3 bg-light-subtle p-3 shadow-sm">
            <div class="d-flex align-items-center mb-2">
                <img src="'.$profile.'" class="rounded-circle border me-2" width="45" height="45" style="object-fit: cover;">
                <div>
                    <h6 class="fw-semibold mb-0">'.$thread->user->name.'</h6>
                    <small class="text-muted">'.$thread->created_at->diffForHumans().'</small>
                </div>
                <span class="badge bg-secondary ms-auto">'.ucfirst($thread->user->role ?? "user").'</span>
            </div>

            <div class="mt-2">
                <h5 class="fw-bold mb-2">'.$thread->title.'</h5>
                '.$image.'
                <p class="text-muted" style="font-size: 0.95rem;">'.Str::limit(strip_tags($thread->content), 150, "...").'</p>
            </div>
        </div>
        ';
    })
            ->addColumn('user', function($thread){
                return $thread->user->name;
            })
            ->addColumn('actions', function($thread){
                $btnDestroy = '<form action="'.route('threads.destroy', $thread->id).'" method="POST">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>';
                $btnView = '<a href="'.route('threads.show', $thread->id).'" class="btn btn-primary btn-sm">View</a> ';
                return '<div class="d-flex gap-2 justify-content-center">' . $btnDestroy . $btnView . '</div>';
            })
            ->rawColumns(['actions', 'user', 'title'])
            ->make(true);
    }

    public function exportPDF($thread)
    {
        $threads = Thread::where('id',$thread)->with(['user', 'tags'])
            ->withCount(['comments', 'likes'])
            ->orderByDesc('created_at')
            ->get();

        $pdf = Pdf::loadView('thread.threads_pdf', compact('threads'));
        $fileName = 'threads_' . date('Y_m_d_H_i_s') . '.pdf';
        return $pdf->download($fileName);
    }

    public function reportedChart()
    {
        $month = now()->format('m');
        $reportedThreads = Thread::where('is_reported', true)->whereMonth('created_at', $month)->get()->groupBy(fn($thread) => $thread->created_at->format('Y-m-d'))->toArray();
        $labels = array_keys($reportedThreads);
        $data = [];
        foreach($reportedThreads as $threadGroup){
            array_push($data, count($threadGroup));
        }
        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
