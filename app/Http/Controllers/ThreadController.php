<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    public function index(request $request)
    {
        $query = Thread::with('user');

    // filter by tag (opsional)
    if ($request->has('tag')) {
        $query->where('tag', $request->tag);
    }

    // urutkan
    if ($request->sort == 'oldest') {
        $query->oldest();
    } elseif ($request->sort == 'random') {
        $query->inRandomOrder();
    } else {
        $query->latest();
    }

    $threads = $query->get();

        return view('thread.index' , compact('threads'));
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
            'tag' => 'required'
        ],[
            'title.required' => 'title harus diisi',
            'content.required' => 'content harus diisi',
            'tag.required' => 'tag harus diisi'
        ]);

        $createdata = Thread::create([
            'title' => $request->title,
            'content' => $request->content,
            'tag' => $request->tag,
            'user_id' => Auth::id(),
        ]);

        if($createdata){
            return redirect()->route('index')->with('success' , 'berhasil membuat thread');
        }else{
            return redirect()->back()->with('error' , 'thread gagal ditambah');
        }
    }


    public function draftIndex(){
        return view('draft.index');
    }

    public function draftCreate(){
        return view('draft.create');
    }

    public function draftStore(){
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

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
}
