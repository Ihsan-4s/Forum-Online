<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id','title', 'content', 'image' , 'status'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'thread_tags', 'thread_id', 'tag_id');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

}

