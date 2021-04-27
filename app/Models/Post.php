<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'post_title', 'post_slug', 'post_type', 'post_content', 'post_status'
    ];

    static function getPosts(){
        $results = Post::leftJoin('users', 'posts.user_id', '=', 'users.id')
                -> select('posts.*', 'users.name as post_author')
                -> where('post_type', 'post')
                -> latest()
                -> paginate(10)
                -> onEachSide(1);

        return $results;
    }

    static function getFpPosts(){
        $results = Post::leftJoin('post_metas', 'posts.id', '=', 'post_metas.post_id')
                -> select('posts.*', 'post_metas.key as meta_key', 'post_metas.value as meta_value')
                -> where('post_type', 'post')
                -> limit(9)
                -> latest()
                -> get();

        return $results;
    }

    static function getBlogPosts(){
        $results = Post::leftJoin('users', 'posts.user_id', '=', 'users.id')
                -> join('post_metas', 'posts.id', '=', 'post_metas.post_id')
                -> select('posts.*', 'users.name as post_author', 'post_metas.value as meta_value')
                -> latest()
                -> paginate(10);

        return $results;
    }

    function postMeta(){
        return $this->hasMany(PostMeta::class);
    }
}
