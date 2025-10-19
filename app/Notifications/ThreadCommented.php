<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Comment;
use App\Models\Thread;

class ThreadCommented extends Notification
{
    use Queueable;

    protected $comment;
    protected $thread;

    public function __construct(Comment $comment, Thread $thread)
    {
        $this->comment = $comment;
        $this->thread = $thread;
    }

    // channel notifikasi, misal database
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'thread_id' => $this->thread->id,
            'thread_title' => $this->thread->title,
            'comment_id' => $this->comment->id,
            'comment_body' => $this->comment->body,
            'commented_by' => $this->comment->user->name,
        ];
    }
}
