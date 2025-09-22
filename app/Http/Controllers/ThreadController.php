<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    public function index(Request $request){
    $query = Thread::with('user');

    if ($request->has('q') && $request->q != '') {
        $query->where(function($sub) use ($request) {
            $sub->where('title', 'like', '%' . $request->q . '%')
                ->orWhere('content', 'like', '%' . $request->q . '%')
                ->orWhere('tag', 'like', '%' . $request->q . '%');
        });
    }

    if ($request->has('tag') && $request->tag != '') {
        $query->where('tag', $request->tag);
    }

    if ($request->sort == 'oldest') {
        $query->oldest();
    } elseif ($request->sort == 'random') {
        $query->inRandomOrder();
    } else {
        $query->latest();
    }

    $threads = $query->get();

    return view('home', compact('threads'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tag' => 'nullable|string|max:100',

        ]);

        $createdThread = Thread::create([
            'user_id' => auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'tag' => $request->tag,
        ]);
        if ($createdThread) {
            return redirect()->route('threads.index')->with('success', 'Thread created successfully.');
        } else {
            return back()->with('error', 'Failed to create thread. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Thread $thread)
    {
        //
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
