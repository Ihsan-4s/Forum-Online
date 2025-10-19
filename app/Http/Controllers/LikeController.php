<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'likeable_id' => 'required|integer',
            'likeable_type' => 'required|string',
        ]);

        $user = Auth::user(); // aman runtime
        if (!$user) {
            return redirect()->route('login');
        }

        $modelClass = match ($request->likeable_type) {
            'thread' => Thread::class,
            'comment' => Comment::class,
            default => null,
        };

        if (!$modelClass) {
            return back()->withErrors(['Invalid type']);
        }

        $item = $modelClass::findOrFail($request->likeable_id);

        $like = $item->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete(); // unlike
        } else {
            $item->likes()->create(['user_id' => $user->id]); // like
        }

        return back();
    }
}
