<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ThreadController extends Controller
{
    public function index(Request $request)
{
    $query = Thread::with('user')
                ->where('status', 'published')
                ->withCount('comments');

    // filter by tag
    if ($request->has('tag')) {
        $query->where('tag', $request->tag);
    }
    // sorting
    if ($request->sort == 'oldest') {
        $query->oldest();
    } elseif ($request->sort == 'random') {
        $query->inRandomOrder();
    } else {
        $query->latest();
    }

    $threads = $query->get();

    return view('thread.index', compact('threads'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('thread.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'content' => 'required',
        'tag' => 'required',
        'image' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048'
    ], [
        'title.required' => 'title harus diisi',
        'content.required' => 'content harus diisi',
        'tag.required' => 'tag harus diisi',
        'image.image' => 'file yang diunggah harus berupa gambar',
        'image.mimes' => 'format image harus sesuai (png, jpg, jpeg, svg)',
        'image.max' => 'ukuran maksimal 2MB'
    ]);
    $filePath = null;


    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = 'image-' . rand(1,100) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('image', $fileName, 'public');
    }

    $createData = Thread::create([
        'title' => $request->title,
        'content' => $request->content,
        'image' => $filePath,
        'tag' => $request->tag,
        'user_id' => Auth::id(),
        'status' => 'published'
    ]);

    if ($createData) {
        return redirect()->route('index')->with('success', 'Berhasil membuat thread');
    } else {
        return redirect()->back()->with('error', 'Thread gagal ditambah');
    }
}



    public function draftIndex(){
        $drafts = Thread::where('user_id', Auth::id())
                ->where('status', 'draft')
                ->latest()
                ->get();

        return view('account',  compact('drafts'));
    }



    public function draftStore(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'tag' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048'
        ],[
            'title.required' => 'title harus diisi',
            'content.required' => 'content harus diisi',
            'tag.required' => 'tag harus diisi',
            'image.image' => 'file yang diunggah harus berupa gambar',
            'image.mimes' => 'format image harus sesuai (png, jpg, jpeg, svg)',
            'image.max' => 'ukuran maksimal 2MB'
        ]);
        $filePath = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'image-' . rand(1,100) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('image', $fileName, 'public');
        }
        
        $createdata = Thread::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $filePath,
            'tag' => $request->tag,
            'user_id' => Auth::id(),
            'status' => 'draft'
        ]);



        if($createdata){
            return redirect()->route('account')->with('success' , 'berhasil tersimpan di draft');
        }else{
            return redirect()->back()->with('error' , 'gagal ditambah');
        }
    }

    public function draftEdit($id){
        $draft = Thread::find($id);
        return view('draft.edit', compact('draft'));
    }

    public function draftUpdate(Request $request, $id)
{
    $request->validate([
        'title' => 'required',
        'content' => 'required',
        'tag' => 'required'
    ]);

    $draft = Thread::findOrFail($id);

    $draft->update([
        'title' => $request->title,
        'content' => $request->content,
        'tag' => $request->tag,
        'status' => 'draft'
    ]);

    return redirect()->route('account.index')->with('success', 'Draft berhasil diperbarui');
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
        //
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
