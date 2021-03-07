<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_title', 'post_slug', 'post_type', 'post_content', 'post_status'
    ];

    static function getPosts(){
        $results = Post::leftJoin('users', 'posts.user_id', '=', 'users.id')
                -> select('posts.*', 'users.name as post_author')
                -> where('post_type', 'post')
                -> latest()
                -> paginate(10);
        
        return $results;
    }

    function postMeta(){
        return $this->hasMany(PostMeta::class);
    }
}
