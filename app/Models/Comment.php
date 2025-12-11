<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{

    use hasFactory;

    protected $fillable = ['thread_id', 'user_id', 'content', 'is_reported', 'report_reason'];

    public function thread(){
        return $this->belongsTo(Thread::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

}


