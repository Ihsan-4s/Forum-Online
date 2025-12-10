<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ThreadCommented;
use Yajra\DataTables\Facades\DataTables;

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

    public function destroy(Thread $thread, Comment $comment)
    {
        if (Auth::id() === $comment->user_id || Auth::user()->isAdmin()) {
            $comment->delete();
            return redirect()->back()->with('success', 'Komentar berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus komentar ini.');
        }
    }

    public function report(Request $request, Thread $thread, Comment $comment){
        // Pastikan user tidak bisa melaporkan komentarnya sendiri
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $comment->update([
            'is_reported' => true,
            'report_reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'Komentar telah dilaporkan. Terima kasih atas laporannya.');
    }

    public function adminComments(){
        $reportedComments = Comment::where('is_reported', true)->get();
        return view('admin.comments', compact('reportedComments'));
    }

    public function adminDestroy(Comment $comment){
        $comment->delete();
        return redirect()->back()->with('success', 'Komentar berhasil dihapus oleh admin.');
    }

    public function exportReportedComments()
    {
        $fileName = 'reported_comments_' . date('Y_m_d_H_i_s') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\CommentReportedExport, $fileName);
    }

    public function commentData()
    {
        $comments = Comment::where('is_reported', 1)->with('user', 'thread')->get();
        return DataTables::of($comments)
            ->addIndexColumn()
            ->addColumn('actions', function($comment) {
                $deleteUrl = route('admin.comments.destroy', $comment->id);
                return '<form method="POST" action="'.$deleteUrl.'" onsubmit="return confirm(\'Are you sure you want to delete this comment?\');">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
