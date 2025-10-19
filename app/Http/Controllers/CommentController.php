<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ThreadCommented;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Thread $thread)
    {
        // Validasi input
        $request->validate([
            'content' => 'required|string',
        ]);

        // Simpan komentar ke database
        $comment = Comment::create([
            'content' => $request->input('content'),
            'thread_id' => $thread->id,  // ambil dari route model binding
            'user_id' => Auth::id(),
        ]);

        // Pastikan relasi user ter-load supaya nama bisa dipakai di notifikasi
        $comment->load('user');

        // Kirim notifikasi ke pemilik thread, kecuali komentar sendiri
        if ($thread->user_id !== Auth::id()) {
            $thread->user->notify(new ThreadCommented($comment, $thread));
        }

        // Redirect balik ke halaman thread dengan pesan sukses
        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}
