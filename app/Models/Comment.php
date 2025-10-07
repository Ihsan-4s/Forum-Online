<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['thread_id', 'user_id', 'content'];

    public function thread(){
        return $this->belongsTo(Thread::class);
    }

    public function user(){
        return $this->beforeQuery(User::class);
    }
}


